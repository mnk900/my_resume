<div class="section-title">Contact</div>
@if($phone)
    <div class="contact-item">
        <span class="contact-label">Phone</span>
        <div class="contact-value">{{ $phone }}</div>
    </div>
@endif
@if($email)
    <div class="contact-item">
        <span class="contact-label">Email</span>
        <div class="contact-value">{{ $email }}</div>
    </div>
@endif
@if($linkedin)
    <div class="contact-item">
        <span class="contact-label">LinkedIn</span>
        <div class="contact-value">{{ $linkedin }}</div>
    </div>
@endif
@if($location)
    <div class="contact-item" style="border-bottom:none;margin:0;padding:0;">
        <span class="contact-label">Location</span>
        <div class="contact-value">{{ $location }}</div>
    </div>
@endif
@if(!empty($skills))
    <div class="section-title">Technical Skills</div>
    @foreach($skills as $cat => $list)
        <div class="skill-category">
            <div class="skill-cat-title">{{ $cat }}</div>
            @foreach($list as $s)
                <div class="skill-item">• {{ $s }}</div>
            @endforeach
        </div>
    @endforeach
@endif
@if(!empty($certifications))
    <div class="section-title">Certifications</div>
    @foreach($certifications as $cert)
        <div class="skill-item">• {{ $cert }}</div>
    @endforeach
@endif
@if(!empty($trainings))
    <div class="section-title">Training</div>
    @foreach($trainings as $train)
        <div class="skill-item">• {{ $train }}</div>
    @endforeach
@endif
@if(!empty($education))
    <div class="section-title">Education</div>
    @foreach($education as $edu)
        <div class="edu-item">
            <div class="edu-degree">{{ $edu['degree'] }}</div>
            <div class="edu-institution">{{ $edu['institution'] }}</div>
            <div class="edu-year">{{ $edu['start_date'] }} - {{ $edu['end_date'] }}</div>
        </div>
    @endforeach
@endif
