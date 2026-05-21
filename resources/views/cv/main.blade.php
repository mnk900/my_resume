<!-- Main content extracted from original template -->
<div class="name">{{ strtoupper($name) }}</div>
<div class="title">{{ $title }}</div>
@if($summary)
    <div class="summary">{{ $summary }}</div>
@endif
@if(!empty($experience))
    <div class="section-title" style="margin-top:10px;">Professional Experience</div>
    @foreach($experience as $exp)
        <div class="item">
            <div class="item-title">{{ $exp['position'] }}</div>
            <div class="item-subtitle">{{ $exp['company'] }}</div>
            <div class="item-meta">{{ $exp['start_date'] }} - {{ $exp['end_date'] }}</div>
            <div class="item-description">{!! nl2br(e($exp['description'])) !!}</div>
        </div>
    @endforeach
@endif
@if(!empty($projects))
    <div class="section-title">Flagship Projects</div>
    @foreach(array_slice($projects,0,5) as $p)
        <div class="project">
            <div class="project-title">{{ $p['title'] }}</div>
            <div class="project-desc">{!! nl2br(e($p['description'])) !!}</div>
        </div>
    @endforeach
@endif
