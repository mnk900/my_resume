<?php

namespace App\Services;

use App\Models\MockInterview;
use App\Models\MockInterviewQuestion;
use App\Models\Opportunity;
use App\Models\User;

class MockInterviewService
{
    /**
     * Generate a new mock interview session for a candidate and target job title/opportunity.
     */
    public function generateSession(User $user, ?Opportunity $opportunity = null, ?string $customJobTitle = null): MockInterview
    {
        $jobTitle = $opportunity ? $opportunity->title : ($customJobTitle ?? 'Professional Role');
        $portfolio = $user->portfolio;

        // Target skills
        $skills = [];
        if ($opportunity && $opportunity->skills->isNotEmpty()) {
            $skills = $opportunity->skills->pluck('skill_name')->toArray();
        } elseif ($portfolio && $portfolio->skills->isNotEmpty()) {
            $skills = $portfolio->skills->take(5)->pluck('name')->toArray();
        } else {
            $skills = ['Communication', 'Problem Solving', 'Project Management', 'Technical Knowledge'];
        }

        $session = MockInterview::create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity?->id,
            'job_title' => $jobTitle,
            'target_skills' => $skills,
            'status' => 'in_progress',
        ]);

        // Generate 5 category questions based on job description & user profile
        $questions = $this->buildQuestions($jobTitle, $skills, $opportunity, $portfolio);

        foreach ($questions as $index => $qData) {
            MockInterviewQuestion::create([
                'mock_interview_id' => $session->id,
                'question_number' => $index + 1,
                'question_category' => $qData['category'],
                'question_text' => $qData['question'],
                'expected_key_points' => $qData['key_points'],
                'sample_improved_answer' => $qData['sample_answer'],
            ]);
        }

        return $session->load('questions');
    }

    /**
     * Evaluate submitted answers and produce the final interview report.
     */
    public function evaluateAnswers(MockInterview $session, array $answers): MockInterview
    {
        $questions = $session->questions;
        $totalScore = 0;
        $technicalScoreSum = 0;
        $techCount = 0;

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if (empty($userAnswer)) {
                $question->update([
                    'user_answer' => 'No answer provided.',
                    'score' => 0,
                    'feedback' => 'Question was skipped or left blank.',
                ]);
                continue;
            }

            $evaluation = $this->gradeSingleAnswer($question, $userAnswer);
            $question->update([
                'user_answer' => $userAnswer,
                'score' => $evaluation['score'],
                'feedback' => $evaluation['feedback'],
            ]);

            $totalScore += $evaluation['score'];
            if (in_array($question->question_category, ['technical', 'role_specific'])) {
                $technicalScoreSum += $evaluation['score'];
                $techCount++;
            }
        }

        $qCount = max(1, $questions->count());
        $overallScore = (int) round($totalScore / $qCount);
        $technicalScore = $techCount > 0 ? (int) round($technicalScoreSum / $techCount) : $overallScore;
        $communicationScore = (int) round(min(100, $overallScore + rand(5, 10)));

        $readiness = 'Needs Work';
        if ($overallScore >= 80) {
            $readiness = 'High';
        } elseif ($overallScore >= 60) {
            $readiness = 'Moderate';
        }

        $summary = "Completed mock interview for {$session->job_title}. Overall performance score: {$overallScore}%. Readiness level: {$readiness}.";
        
        $detailedReport = [
            'strengths' => [
                'Demonstrated willingness to articulate solutions clearly.',
                'Good alignment with core domain expectations.',
            ],
            'weaknesses' => [
                'Could provide more concrete metrics (e.g. % improvements, team sizes).',
                'Structure answers using the STAR method (Situation, Task, Action, Result).',
            ],
            'preparation_tips' => [
                'Practice quantifiable impact statements.',
                'Review deep technical architecture concepts related to target skills.',
            ],
        ];

        $session->update([
            'status' => 'completed',
            'overall_score' => $overallScore,
            'technical_score' => $technicalScore,
            'communication_score' => $communicationScore,
            'readiness_rating' => $readiness,
            'feedback_summary' => $summary,
            'detailed_report' => $detailedReport,
            'completed_at' => now(),
        ]);

        return $session->fresh(['questions']);
    }

    private function gradeSingleAnswer(MockInterviewQuestion $question, string $answer): array
    {
        $wordCount = str_word_count($answer);
        $keyPoints = $question->expected_key_points ?? [];
        $matchedPoints = 0;

        foreach ($keyPoints as $kp) {
            if (str_contains(strtolower($answer), strtolower($kp))) {
                $matchedPoints++;
            }
        }

        $score = 50; // base score for answering
        if ($wordCount > 40) $score += 20;
        if ($wordCount > 80) $score += 10;
        
        if (count($keyPoints) > 0) {
            $score += (int) round(($matchedPoints / count($keyPoints)) * 20);
        } else {
            $score += 15;
        }

        $score = min(100, max(10, $score));

        $feedback = "Solid response containing {$wordCount} words. ";
        if ($score >= 80) {
            $feedback .= "Great job addressing key aspects of the question.";
        } elseif ($score >= 60) {
            $feedback .= "Good response, but consider adding more specific examples and technical depth.";
        } else {
            $feedback .= "Try to expand your response with clearer context and measurable outcomes.";
        }

        return [
            'score' => $score,
            'feedback' => $feedback,
        ];
    }

    private function buildQuestions(string $jobTitle, array $skills, ?Opportunity $opportunity, $portfolio): array
    {
        $skillStr = !empty($skills) ? implode(', ', array_slice($skills, 0, 3)) : 'your primary domain';

        return [
            [
                'category' => 'technical',
                'question' => "Can you explain how you utilize {$skillStr} to solve complex technical problems in your projects?",
                'key_points' => ['best practices', 'performance', 'scalability', 'debugging'],
                'sample_answer' => "I leverage {$skillStr} by adhering to clean code architecture, writing comprehensive tests, and measuring system bottlenecks to ensure high reliability.",
            ],
            [
                'category' => 'role_specific',
                'question' => "As a candidate for the {$jobTitle} role, what key methodologies or frameworks do you prioritize when starting a new major project?",
                'key_points' => ['planning', 'agile', 'requirements gathering', 'execution'],
                'sample_answer' => "I start by conducting thorough requirements analysis, breaking tasks into iterative sprints, establishing CI/CD pipelines, and aligning closely with stakeholders.",
            ],
            [
                'category' => 'experience_based',
                'question' => "Walk me through a challenging project listed in your experience/portfolio. What was your exact contribution and outcome?",
                'key_points' => ['role', 'challenge', 'action', 'measurable result'],
                'sample_answer' => "In my recent project, I refactored key modules, improving overall efficiency by 35% and reducing infrastructure overhead.",
            ],
            [
                'category' => 'behavioral',
                'question' => "Describe a situation where you had a disagreement with a team member or client regarding technical decisions. How did you handle it?",
                'key_points' => ['active listening', 'collaboration', 'data-driven decision', 'compromise'],
                'sample_answer' => "I listened attentively to understand their perspective, presented benchmark performance data objectively, and worked together to find a mutually agreed solution.",
            ],
            [
                'category' => 'situational',
                'question' => "If you join our team and are assigned a mission-critical feature with a tight deadline and incomplete specifications, what steps would you take?",
                'key_points' => ['clarification', 'prioritization', 'MVP', 'communication'],
                'sample_answer' => "I would immediately clarify core deliverables with the product manager, scope down to an MVP, establish risk mitigation plans, and communicate progress daily.",
            ],
        ];
    }
}
