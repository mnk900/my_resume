<!-- resources/views/components/sidebar.blade.php -->
<div class="bg-dark text-white p-4" style="width: 250px; min-height: 100vh;">
    <h4 class="mb-3">Contact Visibility</h4>
    <form method="POST" action="{{ route('profile.visibility.update') }}">
        @csrf
        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_email" id="email_show" value="show" {{ Auth::user()->portfolio->show_email ? 'checked' : '' }}>
                <label class="form-check-label" for="email_show">Show</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_email" id="email_hide" value="hide" {{ Auth::user()->portfolio->show_email ? '' : 'checked' }}>
                <label class="form-check-label" for="email_hide">Hide</label>
            </div>
        </div>
        <!-- Phone -->
        <div class="mb-3">
            <label class="form-label">Phone</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_phone" id="phone_show" value="show" {{ Auth::user()->portfolio->show_phone ? 'checked' : '' }}>
                <label class="form-check-label" for="phone_show">Show</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_phone" id="phone_hide" value="hide" {{ Auth::user()->portfolio->show_phone ? '' : 'checked' }}>
                <label class="form-check-label" for="phone_hide">Hide</label>
            </div>
        </div>
        <!-- LinkedIn -->
        <div class="mb-3">
            <label class="form-label">LinkedIn</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_linkedin" id="li_show" value="show" {{ Auth::user()->portfolio->show_linkedin ? 'checked' : '' }}>
                <label class="form-check-label" for="li_show">Show</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="show_linkedin" id="li_hide" value="hide" {{ Auth::user()->portfolio->show_linkedin ? '' : 'checked' }}>
                <label class="form-check-label" for="li_hide">Hide</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
    </form>
</div>
