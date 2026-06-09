<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h2 class="h4 mb-0">{{ __('Elite Portfolio CMS') }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('cv.download.pdf', Auth::user()->username) }}" class="btn btn-success shadow-sm">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF CV
                </a>
                <a href="{{ route('cv.download.word', Auth::user()->username) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-file-earmark-word me-2"></i>Download Word CV
                </a>
                <a href="{{ route('portfolio.show', Auth::user()->username) }}" target="_blank" class="btn btn-dark shadow-sm">
                    <i class="bi bi-eye me-2"></i>View Live Portfolio
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row gx-4 gy-4">
        <!-- Main Configuration -->
        <div class="col-md-12 col-lg-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Identity & Theme</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('portfolio.update') }}" enctype="multipart/form-data">
                        @csrf
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible shadow-sm border-0 fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ str_replace('-', ' ', ucfirst(session('status'))) }} successfully.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3 text-center">
                            @if($portfolio->profile_image)
                                <img src="{{ Storage::url($portfolio->profile_image) }}" class="rounded-circle shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <input type="file" name="profile_image" class="form-control form-control-sm">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Name Display</label>
                            <input name="title" type="text" class="form-control" value="{{ $portfolio->title }}" required>
                        </div>

                        <div class="p-3 bg-light rounded border mb-3">
                            <label class="form-label small fw-bold d-block mb-2">Account Information</label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <input name="position" type="text" class="form-control" value="{{ $portfolio->position }}" placeholder="Position / Hero Subtitle">
                                </div>
                                <div class="col-12">
                                    <input name="organization" type="text" class="form-control" value="{{ $portfolio->organization }}" placeholder="Organization">
                                </div>
                                <div class="col-md-6">
                                    <input name="city" type="text" class="form-control" value="{{ $portfolio->city }}" placeholder="City">
                                </div>
                                <div class="col-md-6">
                                    <input name="country" type="text" class="form-control" value="{{ $portfolio->country }}" placeholder="Country">
                                </div>
                                <div class="col-md-6">
                                    <input name="contact_number" type="text" class="form-control" value="{{ $portfolio->contact_number }}" placeholder="Contact Number">
                                </div>
                                <div class="col-md-6">
                                    <input name="linkedin_url" type="url" class="form-control" value="{{ $portfolio->linkedin_url }}" placeholder="LinkedIn Profile URL">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Hero Tagline / Short Intro</label>
                            <textarea name="description" class="form-control js-summernote" data-height="140" rows="2" placeholder="Brief hook for the top section...">{{ $portfolio->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Professional Profile</label>
                            <textarea name="detailed_bio" class="form-control js-summernote" data-height="220" rows="5" placeholder="Deep dive into your journey...">{{ $portfolio->detailed_bio }}</textarea>
                        </div>

                        <div class="p-3 bg-light rounded border mb-3">
                            <label class="form-label small fw-bold">Active Design Theme</label>
                            <select name="theme" class="form-select border-primary shadow-sm">
                                @foreach($themes as $t)
                                    <option value="{{ $t->slug }}" {{ $portfolio->theme == $t->slug ? 'selected' : '' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="p-3 bg-light rounded border mb-4">
                            <label class="form-label small fw-bold d-block mb-3">Portfolio Visibility Controls</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="mb-2 fw-bold small">Email</div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_email" id="show_email_yes" value="show" {{ $portfolio->show_email ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_email_yes">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_email" id="show_email_no" value="hide" {{ $portfolio->show_email ? '' : 'checked' }}>
                                        <label class="form-check-label" for="show_email_no">Hide</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2 fw-bold small">Phone</div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_phone" id="show_phone_yes" value="show" {{ $portfolio->show_phone ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_phone_yes">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_phone" id="show_phone_no" value="hide" {{ $portfolio->show_phone ? '' : 'checked' }}>
                                        <label class="form-check-label" for="show_phone_no">Hide</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2 fw-bold small">LinkedIn</div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_linkedin" id="show_linkedin_yes" value="show" {{ $portfolio->show_linkedin ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_linkedin_yes">Show</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_linkedin" id="show_linkedin_no" value="hide" {{ $portfolio->show_linkedin ? '' : 'checked' }}>
                                        <label class="form-check-label" for="show_linkedin_no">Hide</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm">Update Identity</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Specialized Module Management -->
        <div class="col-md-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-0 overflow-hidden">
                    <ul class="nav nav-tabs border-0" id="moduleTabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active px-4 py-3" data-bs-toggle="tab" data-bs-target="#skillsTab">Skills</button></li>
                        <li class="nav-item"><button class="nav-link px-4 py-3" data-bs-toggle="tab" data-bs-target="#projectsTab">Projects</button></li>
                        <li class="nav-item"><button class="nav-link px-4 py-3" data-bs-toggle="tab" data-bs-target="#expTab">Experience</button></li>
                        <li class="nav-item"><button class="nav-link px-4 py-3" data-bs-toggle="tab" data-bs-target="#eduTab">Education</button></li>
                        <li class="nav-item"><button class="nav-link px-4 py-3" data-bs-toggle="tab" data-bs-target="#inboxTab">Inbox 
                            @php $unread = $portfolio->messages->where('is_read', false)->count(); @endphp
                            @if($unread > 0) <span class="badge bg-danger rounded-pill ms-1">{{ $unread }}</span> @endif
                        </button></li>
                        <li class="nav-item"><button class="nav-link px-4 py-3" data-bs-toggle="tab" data-bs-target="#moreTab">More</button></li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="moduleTabsContent">
                        <!-- Skills -->
                        <div class="tab-pane fade show active" id="skillsTab">
                            <form action="{{ route('modules.skills.store') }}" method="POST" class="p-3 bg-light rounded border mb-4">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-md-3"><input name="name" class="form-control" placeholder="Skill Name" required></div>
                                    <div class="col-md-2"><input name="percentage" type="number" class="form-control" placeholder="%" required></div>
                                    <div class="col-md-3">
                                        <input name="category" class="form-control" placeholder="Category (e.g. Programming)" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="icon" class="form-select">
                                            <option value="code">Icon: Code</option>
                                            <option value="layer-group">Icon: Layers</option>
                                            <option value="database">Icon: Database</option>
                                            <option value="tools">Icon: Tools</option>
                                            <option value="laptop-code">Icon: Laptop</option>
                                            <option value="server">Icon: Server</option>
                                            <option value="mobile-alt">Icon: Mobile</option>
                                            <option value="palette">Icon: Design</option>
                                            <option value="terminal">Icon: Terminal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1"><button class="btn btn-dark w-100"><i class="bi bi-plus-lg"></i></button></div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="small py-2">Category</th>
                                            <th class="small py-2">Skill</th>
                                            <th class="small py-2">Proficiency</th>
                                            <th class="small py-2 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($portfolio->skills as $skill)
                                            <tr>
                                                <td class="small"><i class="fas fa-{{ $skill->icon }} me-2 text-muted"></i>{{ $skill->category }}</td>
                                                <td class="fw-bold">{{ $skill->name }}</td>
                                                <td><div class="progress" style="height: 8px;"><div class="progress-bar" style="width: {{ $skill->percentage }}%"></div></div></td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#editSkill{{ $skill->id }}">Edit</button>
                                                    <form action="{{ route('modules.skills.destroy', $skill) }}" method="POST" class="d-inline">
                                                        @csrf @method('delete')
                                                        <button class="btn btn-sm btn-link text-danger">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr class="collapse" id="editSkill{{ $skill->id }}">
                                                <td colspan="4">
                                                    <form action="{{ route('modules.skills.update', $skill) }}" method="POST" class="p-3 bg-light rounded border">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="row g-2">
                                                            <div class="col-md-3"><input name="name" class="form-control form-control-sm" value="{{ $skill->name }}" required></div>
                                                            <div class="col-md-2"><input name="percentage" type="number" min="0" max="100" class="form-control form-control-sm" value="{{ $skill->percentage }}" required></div>
                                                            <div class="col-md-3"><input name="category" class="form-control form-control-sm" value="{{ $skill->category }}"></div>
                                                            <div class="col-md-2"><input name="icon" class="form-control form-control-sm" value="{{ $skill->icon }}"></div>
                                                            <div class="col-md-2 text-end"><button class="btn btn-sm btn-primary w-100">Update</button></div>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Projects -->
                        <div class="tab-pane fade" id="projectsTab">
                            <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#newProjectForm">+ New Project</button>
                            <form action="{{ route('modules.projects.store') }}" method="POST" enctype="multipart/form-data" class="collapse mb-4 p-3 border rounded" id="newProjectForm">
                                @csrf
                                <div class="mb-3"><input name="title" class="form-control" placeholder="Project Title" required></div>
                                <div class="mb-3"><textarea name="description" class="form-control js-summernote" data-height="160" placeholder="Short Project Description"></textarea></div>
                                <div class="mb-3"><input name="link" class="form-control" placeholder="URL Link"></div>
                                <div class="mb-3"><input type="file" name="image" class="form-control"></div>
                                <button class="btn btn-primary">Save Project</button>
                            </form>
                            <div class="row g-3">
                                @foreach($portfolio->projects as $project)
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded shadow-sm h-100 d-flex justify-content-between flex-column">
                                            <div>
                                                <h6 class="fw-bold">{{ $project->title }}</h6>
                                                <small class="text-muted d-block mb-2">{{ Str::limit(strip_tags($project->description), 60) }}</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline-secondary w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#editProject{{ $project->id }}">Edit</button>
                                            <form action="{{ route('modules.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="collapse border rounded p-2 mb-2" id="editProject{{ $project->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <input name="title" class="form-control form-control-sm mb-2" value="{{ $project->title }}" required>
                                                <textarea name="description" class="form-control form-control-sm mb-2 js-summernote" data-height="140">{{ $project->description }}</textarea>
                                                <input name="link" class="form-control form-control-sm mb-2" value="{{ $project->link }}" placeholder="URL Link">
                                                <input type="file" name="image" class="form-control form-control-sm mb-2">
                                                <button class="btn btn-sm btn-primary w-100">Update Project</button>
                                            </form>
                                            <form action="{{ route('modules.projects.destroy', $project) }}" method="POST">
                                                @csrf @method('delete')
                                                <button class="btn btn-sm btn-outline-danger w-100 mt-2">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Experience -->
                        <div class="tab-pane fade" id="expTab">
                            <form action="{{ route('modules.experiences.store') }}" method="POST" class="p-3 bg-light rounded border mb-4">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6"><input name="company" class="form-control" placeholder="Company Name" required></div>
                                    <div class="col-md-6"><input name="position" class="form-control" placeholder="Job Title" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">Start Date</label><input name="start_date" type="date" class="form-control" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">End Date (Leave blank if Present)</label><input name="end_date" type="date" class="form-control"></div>
                                    <div class="col-12"><textarea name="description" class="form-control js-summernote" data-height="180" placeholder="Job Responsibilities"></textarea></div>
                                    <div class="col-12 text-end"><button class="btn btn-dark">+ Add Experience</button></div>
                                </div>
                            </form>
                            @foreach($portfolio->experiences as $exp)
                                <div class="border-bottom py-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div><div class="fw-bold">{{ $exp->position }}</div><small>{{ $exp->company }} | {{ $exp->start_date->format('M Y') }} - {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}</small></div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editExp{{ $exp->id }}">Edit</button>
                                            <form action="{{ route('modules.experiences.destroy', $exp) }}" method="POST">
                                                @csrf @method('delete')
                                                <button class="btn btn-sm text-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="{{ route('modules.experiences.update', $exp) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editExp{{ $exp->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row g-2">
                                            <div class="col-md-6"><input name="company" class="form-control form-control-sm" value="{{ $exp->company }}" required></div>
                                            <div class="col-md-6"><input name="position" class="form-control form-control-sm" value="{{ $exp->position }}" required></div>
                                            <div class="col-md-6"><input name="start_date" type="date" class="form-control form-control-sm" value="{{ $exp->start_date?->format('Y-m-d') }}" required></div>
                                            <div class="col-md-6"><input name="end_date" type="date" class="form-control form-control-sm" value="{{ $exp->end_date?->format('Y-m-d') }}"></div>
                                            <div class="col-12"><textarea name="description" class="form-control form-control-sm js-summernote" data-height="150">{{ $exp->description }}</textarea></div>
                                            <div class="col-12 text-end"><button class="btn btn-sm btn-primary">Update Experience</button></div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <!-- Education -->
                        <div class="tab-pane fade" id="eduTab">
                            <form action="{{ route('modules.education.store') }}" method="POST" class="p-3 bg-light rounded border mb-4">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6"><input name="institution" class="form-control" placeholder="University" required></div>
                                    <div class="col-md-6"><input name="degree" class="form-control" placeholder="Degree" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">Start Date</label><input name="start_date" type="date" class="form-control" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">End Date</label><input name="end_date" type="date" class="form-control" required></div>
                                    <div class="col-12 text-end"><button class="btn btn-dark">+ Add Education</button></div>
                                </div>
                            </form>
                            @foreach($portfolio->education as $edu)
                                <div class="border-bottom py-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div><div class="fw-bold">{{ $edu->degree }}</div><small>{{ $edu->institution }} | {{ $edu->start_date->format('Y') }} - {{ $edu->end_date->format('Y') }}</small></div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editEdu{{ $edu->id }}">Edit</button>
                                            <form action="{{ route('modules.education.destroy', $edu) }}" method="POST">
                                                @csrf @method('delete')
                                                <button class="btn btn-sm text-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="{{ route('modules.education.update', $edu) }}" method="POST" class="collapse mt-2 p-3 bg-light rounded border" id="editEdu{{ $edu->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row g-2">
                                            <div class="col-md-6"><input name="institution" class="form-control form-control-sm" value="{{ $edu->institution }}" required></div>
                                            <div class="col-md-6"><input name="degree" class="form-control form-control-sm" value="{{ $edu->degree }}" required></div>
                                            <div class="col-md-6"><input name="start_date" type="date" class="form-control form-control-sm" value="{{ $edu->start_date?->format('Y-m-d') }}" required></div>
                                            <div class="col-md-6"><input name="end_date" type="date" class="form-control form-control-sm" value="{{ $edu->end_date?->format('Y-m-d') }}" required></div>
                                            <div class="col-12 text-end"><button class="btn btn-sm btn-primary">Update Education</button></div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <!-- Inbox -->
                        <div class="tab-pane fade" id="inboxTab">
                            <h5 class="fw-bold mb-4">Inquiries & Messages</h5>
                            @foreach($portfolio->messages->sortByDesc('created_at') as $msg)
                                <div class="card mb-3 border {{ $msg->is_read ? 'bg-light' : 'border-primary shadow-sm' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $msg->name }}</h6>
                                                <small class="text-muted">{{ $msg->email }} | {{ $msg->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if(!$msg->is_read)
                                                    <form action="{{ route('messages.read', $msg) }}" method="POST">@csrf <button class="btn btn-sm btn-outline-primary">Mark Read</button></form>
                                                @endif
                                                <form action="{{ route('messages.destroy', $msg) }}" method="POST">@csrf @method('delete') <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                                            </div>
                                        </div>
                                        <p class="mb-3 p-3 bg-white border rounded">"{{ $msg->message }}"</p>
                                        
                                        @if($msg->reply)
                                            <div class="alert alert-secondary py-2 small mb-0">
                                                <strong>My Reply:</strong> {{ $msg->reply }}
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-dark" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $msg->id }}">Reply via Email</button>
                                            <form action="{{ route('messages.reply', $msg) }}" method="POST" class="collapse mt-3" id="replyForm{{ $msg->id }}">
                                                @csrf
                                                <textarea name="reply" class="form-control mb-2" rows="3" placeholder="Write your response here..."></textarea>
                                                <button class="btn btn-primary btn-sm">Send Reply</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($portfolio->messages->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-1 mb-3"></i>
                                    <p>Your inbox is currently empty.</p>
                                </div>
                            @endif
                        </div>

                        <!-- More -->
                        <div class="tab-pane fade" id="moreTab">
                            <div class="accordion border-0" id="moreAccordion">
                                <!-- Services -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#servCol">Services Offered</button></h2>
                                    <div id="servCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.services.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="title" class="form-control mb-2" placeholder="Service Name" required>
                                            <textarea name="description" class="form-control mb-2 js-summernote" data-height="150" placeholder="What you offer..."></textarea>
                                            <button class="btn btn-sm btn-dark w-100">Add Service</button>
                                        </form>
                                        @foreach($portfolio->services as $serv)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <span>{{ $serv->title }}</span>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editService{{ $serv->id }}">Edit</button>
                                                        <form action="{{ route('modules.services.destroy', $serv) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.services.update', $serv) }}" method="POST" class="collapse mt-2" id="editService{{ $serv->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="title" class="form-control form-control-sm mb-2" value="{{ $serv->title }}" required>
                                                    <textarea name="description" class="form-control form-control-sm mb-2 js-summernote" data-height="130">{{ $serv->description }}</textarea>
                                                    <button class="btn btn-sm btn-primary">Update Service</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Certifications -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#certCol">Certifications</button></h2>
                                    <div id="certCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.certifications.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="name" class="form-control mb-2" placeholder="Certification Name" required>
                                            <input name="issuer" class="form-control mb-2" placeholder="Issuing Body" required>
                                            <button class="btn btn-sm btn-dark w-100">Add Certification</button>
                                        </form>
                                        @foreach($portfolio->certifications as $cert)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <span>{{ $cert->name }} ({{ $cert->issuer }})</span>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editCert{{ $cert->id }}">Edit</button>
                                                        <form action="{{ route('modules.certifications.destroy', $cert) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.certifications.update', $cert) }}" method="POST" class="collapse mt-2" id="editCert{{ $cert->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="name" class="form-control form-control-sm mb-2" value="{{ $cert->name }}" required>
                                                    <input name="issuer" class="form-control form-control-sm mb-2" value="{{ $cert->issuer }}" required>
                                                    <button class="btn btn-sm btn-primary">Update Certification</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Achievements -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#achCol">Achievements</button></h2>
                                    <div id="achCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.achievements.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="title" class="form-control mb-2" placeholder="Award/Title" required>
                                            <button class="btn btn-sm btn-dark w-100">Add Achievement</button>
                                        </form>
                                        @foreach($portfolio->achievements as $ach)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <span>{{ $ach->title }}</span>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editAch{{ $ach->id }}">Edit</button>
                                                        <form action="{{ route('modules.achievements.destroy', $ach) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.achievements.update', $ach) }}" method="POST" class="collapse mt-2" id="editAch{{ $ach->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="title" class="form-control form-control-sm mb-2" value="{{ $ach->title }}" required>
                                                    <button class="btn btn-sm btn-primary">Update Achievement</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Trainings -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#trainCol">Trainings & Capacity Building</button></h2>
                                    <div id="trainCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.trainings.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="title" class="form-control mb-2" placeholder="Training Title" required>
                                            <input name="institution" class="form-control mb-2" placeholder="Institution" required>
                                            <button class="btn btn-sm btn-dark w-100">Add Training</button>
                                        </form>
                                        @foreach($portfolio->trainings as $train)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <span>{{ $train->title }} ({{ $train->institution }})</span>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editTrain{{ $train->id }}">Edit</button>
                                                        <form action="{{ route('modules.trainings.destroy', $train) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.trainings.update', $train) }}" method="POST" class="collapse mt-2" id="editTrain{{ $train->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="title" class="form-control form-control-sm mb-2" value="{{ $train->title }}" required>
                                                    <input name="institution" class="form-control form-control-sm mb-2" value="{{ $train->institution }}" required>
                                                    <button class="btn btn-sm btn-primary">Update Training</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Contributions -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#contribCol">Contributions</button></h2>
                                    <div id="contribCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.contributions.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="title" class="form-control mb-2" placeholder="Contribution Title (e.g. Open Source, Community Work)" required>
                                            <textarea name="description" class="form-control mb-2 js-summernote" data-height="150" rows="2" placeholder="Describe your contribution..."></textarea>
                                            <button class="btn btn-sm btn-dark w-100">Add Contribution</button>
                                        </form>
                                        @foreach($portfolio->contributions as $contrib)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <div class="fw-bold">{{ $contrib->title }}</div>
                                                        <div class="text-muted">{{ Str::limit(strip_tags($contrib->description), 80) }}</div>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editContrib{{ $contrib->id }}">Edit</button>
                                                        <form action="{{ route('modules.contributions.destroy', $contrib) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.contributions.update', $contrib) }}" method="POST" class="collapse mt-2" id="editContrib{{ $contrib->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="title" class="form-control form-control-sm mb-2" value="{{ $contrib->title }}" required>
                                                    <textarea name="description" class="form-control form-control-sm mb-2 js-summernote" data-height="130">{{ $contrib->description }}</textarea>
                                                    <button class="btn btn-sm btn-primary">Update Contribution</button>
                                                </form>
                                            </div>
                                        @endforeach
                                        @if($portfolio->contributions->isEmpty())
                                            <p class="text-muted small text-center py-2">No contributions added yet.</p>
                                        @endif
                                    </div>
                                </div>
                                <!-- Testimonials -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#testiCol">Testimonials</button></h2>
                                    <div id="testiCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('modules.testimonials.store') }}" method="POST" class="mb-3">
                                            @csrf
                                            <input name="client_name" class="form-control mb-2" placeholder="Person's Name" required>
                                            <textarea name="content" class="form-control mb-2 js-summernote" data-height="170" rows="3" placeholder="What did they say about you?" required></textarea>
                                            <button class="btn btn-sm btn-dark w-100">Add Testimonial</button>
                                        </form>
                                        @foreach($portfolio->testimonials as $testi)
                                            <div class="small border-bottom py-2">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <div class="fw-bold">{{ $testi->client_name }}</div>
                                                        <div class="text-muted fst-italic">{{ Str::limit(strip_tags($testi->content), 80) }}</div>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-link py-0 small" type="button" data-bs-toggle="collapse" data-bs-target="#editTesti{{ $testi->id }}">Edit</button>
                                                        <form action="{{ route('modules.testimonials.destroy', $testi) }}" method="POST">@csrf @method('delete')<button class="btn btn-link py-0 text-danger small">Delete</button></form>
                                                    </div>
                                                </div>
                                                <form action="{{ route('modules.testimonials.update', $testi) }}" method="POST" class="collapse mt-2" id="editTesti{{ $testi->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input name="client_name" class="form-control form-control-sm mb-2" value="{{ $testi->client_name }}" required>
                                                    <textarea name="content" class="form-control form-control-sm mb-2 js-summernote" data-height="130" required>{{ $testi->content }}</textarea>
                                                    <button class="btn btn-sm btn-primary">Update Testimonial</button>
                                                </form>
                                            </div>
                                        @endforeach
                                        @if($portfolio->testimonials->isEmpty())
                                            <p class="text-muted small text-center py-2">No testimonials added yet.</p>
                                        @endif
                                    </div>
                                </div>
                                <!-- Resume / CV -->
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold rounded" data-bs-toggle="collapse" data-bs-target="#cvCol">Resume / CV Document</button></h2>
                                    <div id="cvCol" class="accordion-collapse collapse p-3">
                                        <form action="{{ route('portfolio.sections.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                            @csrf
                                            <input type="hidden" name="type" value="resume">
                                            <input type="hidden" name="title" value="Professional Resume">
                                            <label class="form-label small fw-bold">Upload PDF version</label>
                                            <input type="file" name="file" class="form-control mb-2" required>
                                            <button class="btn btn-sm btn-dark w-100">Update Resume File</button>
                                        </form>
                                        @php $resume = $portfolio->sections->where('type', 'resume')->first(); @endphp
                                        @if($resume)
                                            <div class="alert alert-info py-2 small d-flex justify-content-between align-items-center">
                                                <span>Current: {{ basename($resume->file_path) }}</span>
                                                <form action="{{ route('portfolio.sections.destroy', $resume) }}" method="POST">@csrf @method('delete')<button class="btn btn-sm text-danger py-0">Remove</button></form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Tab Content -->
                </div> <!-- End Card Body -->
            </div>
        </div>
    </div>
</x-app-layout>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border-color: #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const initEditors = (scope = document) => {
                const $ = window.jQuery;
                $(scope).find('.js-summernote').each(function () {
                    const $el = $(this);
                    if ($el.next('.note-editor').length) {
                        return;
                    }

                    $el.summernote({
                        height: Number($el.data('height')) || 160,
                        placeholder: $el.attr('placeholder') || '',
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['codeview']]
                        ]
                    });
                });
            };

            const loadScript = (src) => new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });

            try {
                if (!window.jQuery) {
                    await loadScript('https://code.jquery.com/jquery-3.7.1.min.js');
                }

                if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.summernote) {
                    await loadScript('https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js');
                }

                initEditors(document);

                window.jQuery(document).on('shown.bs.collapse', '.collapse', function () {
                    initEditors(this);
                });
            } catch (e) {
                console.error('Summernote failed to load:', e);
            }
        });
    </script>
@endpush
