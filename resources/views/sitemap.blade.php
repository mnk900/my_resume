{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Core Public Landing Pages -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/jobs') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/companies') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/talent') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Dynamic Published Jobs -->
    @foreach($jobs as $job)
    <url>
        <loc>{{ url('/job/' . $job->slug) }}</loc>
        <lastmod>{{ ($job->updated_at ?? $job->published_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Dynamic Active Companies -->
    @foreach($companies as $comp)
    <url>
        <loc>{{ url('/company/' . $comp->slug) }}</loc>
        <lastmod>{{ ($comp->updated_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Dynamic Public Candidate Portfolios -->
    @foreach($portfolios as $port)
        @if($port->user && $port->user->username && $port->user->role !== 'admin' && $port->user->account_status !== 'suspended')
        <url>
            <loc>{{ url('/' . $port->user->username) }}</loc>
            <lastmod>{{ ($port->updated_at ?? now())->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endif
    @endforeach
</urlset>
