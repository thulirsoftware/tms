@extends('theme.default')

@section('content')
    <div class="client-container">

        <!-- Header -->
        <div class="client-header">
            <h4>
                Client: <span id="client-name" data-field="name">{{ $client->name }}</span>
            </h4>
            <div class="client-header-actions">
                <a href="{{ route('clients.index') }}" class="btn-secondary">← Back</a>
                @if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Config Tables'))

                    <button id="editBtn" class="btn-edit"><i class="fa fa-edit"></i></button>
                @endif
            </div>
        </div>

        <!-- Client Info -->
        <div class="card">
            <div class="card-body">
                <h6 class="section-title">Client Details</h6>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Name</label>
                        <div id="client-name" class="editable-box" data-field="name" contenteditable="false">
                            {{ $client->name }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Location</label>
                        <div id="client-location" class="editable-box" data-field="location" contenteditable="false">
                            {{ $client->location }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label><br>
                        <select id="client-status" class="form-select client-select" data-field="status" disabled>
                            <option value="active" {{ $client->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Visible</label><br>
                        <select id="client-visible" class="form-select client-select" data-field="isvisible" disabled>
                            <option value="yes" {{ $client->isvisible == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ $client->isvisible == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Description</label>
                        <div id="client-description" class="editable-box" data-field="description" contenteditable="false">
                            {{ $client->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <h6 class="section-title">Projects</h6>
                    <button class="btn-success" id="addProjectBtn" style="display:none;">+ Add Project</button>
                </div>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th class="action-column" style="display:none;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="projectTable">
                        @forelse($client->projects as $p)
                            <tr data-id="{{ $p->id }}">
                                <td contenteditable="false" data-field="projectName">{{ $p->projectName }}</td>
                                <td contenteditable="false" data-field="projectDesc">{{ $p->projectDesc }}</td>
                                <td><input type="date" class="project-input" data-field="startDate"
                                        value="{{ $p->startDate }}" disabled></td>
                                <td><input type="date" class="project-input" data-field="endDate" value="{{ $p->endDate }}"
                                        disabled></td>
                                <td>
                                    <select class="project-input" data-field="status" disabled>
                                        <option value="Open" {{ $p->status == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Closed" {{ $p->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </td>
                                <td class="action-column" style="display:none;">
                                    <button class="btn-danger delete-project"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-msg">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Services -->
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <h6 class="section-title">Services</h6>
                    <button class="btn-success" id="addServiceBtn" style="display:none;">+ Add Service</button>
                </div>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="action-column" style="display:none;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="serviceTable">
                        @forelse($client->services as $s)
                            <tr data-id="{{ $s->id }}">
                                <td contenteditable="false" data-field="name">{{ $s->name }}</td>
                                <td contenteditable="false" data-field="description">{{ $s->description }}</td>
                                <td>
                                    <select class="service-input" data-field="status" disabled>
                                        <option value="active" {{ $s->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $s->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </td>
                                <td class="action-column" style="display:none;">
                                    <button class="btn-danger delete-service"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-msg">No services found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Dependencies --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            const clientId = {{ $client->id }};
            const token = '{{ csrf_token() }}';
            let editMode = false;

            function ajaxUpdate(url, data) {
                $.post(url, { ...data, _token: token })
                    .done(() => console.log('✅ Updated:', url, data))
                    .fail(() => console.warn('❌ Update failed', url, data));
            }

            function toggleEditingUI(enable) {
                editMode = enable;

                // Toggle header button text
                $('#editBtn').html(editMode ? 'Save' : '<i class="fa fa-edit"></i>');

                // ---- CLIENT FIELDS: convert visible boxes to inputs (and back) ----
                $('.editable-box').each(function () {
                    const $box = $(this);
                    const field = $box.data('field');
                    const type = $box.data('type') || 'text';

                    if (editMode) {
                        if ($box.data('editing')) return;
                        if (type === 'textarea') {
                            const $ta = $(`<textarea class="editable-input" data-field="${field}"></textarea>`);
                            $ta.val($box.text().trim());
                            $box.hide().after($ta);
                        } else {
                            const $inp = $(`<input type="text" class="editable-input" data-field="${field}">`);
                            $inp.val($box.text().trim());
                            $box.hide().after($inp);
                        }
                        $box.data('editing', true);
                    } else {
                        const $input = $box.next(`.editable-input[data-field="${field}"]`);
                        if ($input.length) {
                            $box.text($input.val());
                            $input.remove();
                            $box.show();
                            $box.data('editing', false);
                        }
                    }
                });

                // ---- PROJECTS & SERVICES: convert td[data-field] to inputs while editing ----
                $('#projectTable tr, #serviceTable tr').each(function () {
                    $(this).find('td[data-field]').each(function () {
                        const $td = $(this);
                        const field = $td.data('field');

                        if (editMode) {
                            if ($td.data('editing')) return;
                            const val = $td.text().trim();
                            const $inp = $(`<input type="text" class="editable-input table-input" data-field="${field}">`).val(val);
                            $td.empty().append($inp);
                            $td.data('editing', true);
                        } else {
                            const $input = $td.find('.editable-input');
                            if ($input.length) {
                                $td.text($input.val());
                                $td.data('editing', false);
                            }
                        }
                    });
                });

                // enable/disable selects & date inputs and add editing class for styling
                $('.client-select, .project-input, .service-input').prop('disabled', !editMode);
                $('.client-select, .project-input, .service-input').toggleClass('input-editing', editMode);

                // show/hide add/delete controls
                $('#addProjectBtn, #addServiceBtn').toggle(editMode);
                $('.action-column, .delete-project, .delete-service').toggle(editMode);
            }

            // Edit button click
            $('#editBtn').click(function () {
                // toggle
                toggleEditingUI(!editMode);

                // If we switched to view mode => save
                if (editMode === false) {
                    saveAllChanges();
                } else {
                    // focus first input for convenience
                    setTimeout(() => $('.editable-input:first').focus(), 50);
                }
            });

            function saveAllChanges() {
                // ---- CLIENT simple fields ----
                $('.editable-box').each(function () {
                    const field = $(this).data('field');
                    const $input = $(this).next(`.editable-input[data-field="${field}"]`);
                    const value = $input.length ? $input.val().trim() : $(this).text().trim();
                    ajaxUpdate(`/clients/${clientId}/update`, { [field]: value });
                });

                // ---- CLIENT selects ----
                $('select[data-field]').each(function () {
                    const field = $(this).data('field');
                    const val = $(this).val();
                    console.log("Updating field:", field, "Value:", val);
                    ajaxUpdate(`/clients/${clientId}/update`, { [field]: val });
                });

                // ---- PROJECT rows ----
                $('#projectTable tr').each(function () {
                    const row = $(this);
                    const id = row.data('id');
                    if (!id) return;
                    const payload = {};
                    row.find('[data-field]').each(function () {
                        const $el = $(this);
                        const field = $el.data('field');
                        let value = '';
                        const $inp = $el.find('.editable-input');
                        if ($inp.length) value = $inp.val();
                        else if ($el.is('input,textarea,select')) value = $el.val();
                        else value = $el.text().trim();
                        payload[field] = value;
                    });
                    ajaxUpdate(`/clients/projects/${id}/update`, payload);
                });

                // ---- SERVICE rows ----
                $('#serviceTable tr').each(function () {
                    const row = $(this);
                    const id = row.data('id');
                    if (!id) return;
                    const payload = {};
                    row.find('[data-field]').each(function () {
                        const $el = $(this);
                        const field = $el.data('field');
                        let value = '';
                        const $inp = $el.find('.editable-input');
                        if ($inp.length) value = $inp.val();
                        else if ($el.is('input,textarea,select')) value = $el.val();
                        else value = $el.text().trim();
                        payload[field] = value;
                    });
                    ajaxUpdate(`/clients/services/${id}/update`, payload);
                });
            }

            // Add project/service handlers remain same but ensure new rows are editable-ready
            $('#addProjectBtn').click(function () {
                $.post(`/clients/${clientId}/projects/store`, {
                    _token: token,
                    projectName: '',
                    projectDesc: '',
                    startDate: null,
                    endDate: '',
                    status: 'Open'
                }).done(function (response) {
                    const p = response.project;
                    const newRow = `
                                                <tr data-id="${p.id}">
                                                    <td data-field="projectName">${p.projectName || ''}</td>
                                                    <td data-field="projectDesc">${p.projectDesc || ''}</td>
                                                    <td><input type="date" class="project-input" data-field="startDate" value="${p.startDate || ''}" disabled></td>
                                                    <td><input type="date" class="project-input" data-field="endDate" value="${p.endDate || ''}" disabled></td>
                                                    <td>
                                                        <select class="project-input" data-field="status" disabled>
                                                            <option value="Open" ${p.status === 'Open' ? 'selected' : ''}>Open</option>
                                                            <option value="Closed" ${p.status === 'Closed' ? 'selected' : ''}>Closed</option>
                                                        </select>
                                                    </td>
                                                    <td class="action-column" style="display:none;">
                                                        <button class="btn-danger delete-project"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>`;
                    $('#projectTable').append(newRow);

                    // if we're currently in editMode, re-enable editing UI for the newly appended row
                    if (editMode) toggleEditingUI(true);
                }).fail(function () {
                    alert('Failed to add project');
                });
            });

            $('#addServiceBtn').click(function () {
                $.post(`/clients/${clientId}/services/store`, {
                    _token: token,
                    name: '',
                    description: '',
                    status: 'active'
                }).done(function (response) {
                    const s = response.service;
                    const newRow = `
                                                <tr data-id="${s.id}">
                                                    <td data-field="name">${s.name || ''}</td>
                                                    <td data-field="description">${s.description || ''}</td>
                                                    <td>
                                                        <select class="service-input" data-field="status" disabled>
                                                            <option value="active" ${s.status === 'active' ? 'selected' : ''}>Active</option>
                                                            <option value="inactive" ${s.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                                        </select>
                                                    </td>
                                                    <td class="action-column" style="display:none;">
                                                        <button class="btn-danger delete-service"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>`;
                    $('#serviceTable').append(newRow);
                    if (editMode) toggleEditingUI(true);
                }).fail(function () {
                    alert('Failed to add service');
                });
            });

            // delete handlers (unchanged behaviour)
            $('#projectTable').on('click', '.delete-project', function () {
                if (confirm('Delete this project?')) {
                    const id = $(this).closest('tr').data('id');
                    $.ajax({
                        url: `/clients/projects/${id}`,
                        type: 'DELETE',
                        data: { _token: token },
                        success: () => $(this).closest('tr').remove(),
                        error: () => alert('Failed to delete')
                    });
                }
            });

            $('#serviceTable').on('click', '.delete-service', function () {
                if (confirm('Delete this service?')) {
                    const id = $(this).closest('tr').data('id');
                    $.ajax({
                        url: `/clients/services/${id}`,
                        type: 'DELETE',
                        data: { _token: token },
                        success: () => $(this).closest('tr').remove(),
                        error: () => alert('Failed to delete')
                    });
                }
            });
        });
    </script>


    <style>
        /* 🌟 General Layout */
        .client-container {
            padding: 20px 40px;
            font-family: "Poppins", sans-serif;
            animation: fadeIn 0.6s ease;
        }

        /* ✨ Header */
        .client-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .client-header h4 {
            color: #2b2b2b;
            font-weight: 600;
        }

        .client-header-actions {
            display: flex;
            gap: 10px;
        }

        /* 🎛 Buttons */
        .btn-edit,
        .btn-secondary,
        .btn-success,
        .btn-danger {
            border: none;
            border-radius: 20px;
            cursor: pointer;
            padding: 8px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #ffffff;
            color: #4b49ac;
            font-size: 18px;
        }

        .btn-edit:hover {
            box-shadow: 0 4px 12px rgba(75, 73, 172, 0.3);
        }

        .btn-secondary {
            background: #f2f2f2;
            color: #444;
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745, #20c997);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
            color: white;
        }

        /* 🪄 Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .card-body {
            padding: 25px;
        }

        /* 🧾 Section Title */
        .section-title {
            color: #4b49ac;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* 🧩 Editable Fields */
        .editable-box {
            background: #f9fafc;
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 8px;
            min-height: 40px;
            transition: all 0.3s ease;
        }

        .editable-box[contenteditable="true"] {
            background: #fff;
            border-color: #4b49ac;
            box-shadow: 0 0 0 2px rgba(75, 73, 172, 0.2);
        }

        /* 🧮 Form Layout */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        /* 🎛️ Select & Inputs */
        .select-wrapper select,
        input[type="date"] {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 15px;
            background: #f9fafc;
            transition: all 0.3s ease;
        }

        select:disabled,
        input:disabled {
            border: none;
            background: transparent;
            color: #333;
            pointer-events: none;
            appearance: none;
        }

        /* 🧾 Table */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .styled-table th {
            background: rgba(3, 75, 134, 0.85);
            color: white;
            padding: 12px;
            text-align: left;
        }

        .styled-table td {
            background: #fff;
            padding: 10px;
            border-top: 1px solid #eee;
            transition: background 0.3s ease;
        }

        .styled-table tr:hover td {
            background: #f3f7ff;
        }

        .empty-msg {
            text-align: center;
            color: #888;
        }

        /* 🎞 Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Editable input styling */
        .editable-input {
            width: 100%;
            box-sizing: border-box;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #d0d6e6;
            min-height: 40px;
            font-size: 15px;
            background: #fff;
            transition: box-shadow .15s ease, border-color .15s ease;
            font-family: inherit;
        }

        /* slightly different for table-inline inputs */
        .table-input {
            padding: 6px 10px;
            font-size: 14px;
            border-radius: 6px;
        }

        /* When selects / date inputs are enabled for editing, show form-control look */
        .input-editing {
            border: 1px solid #d0d6e6 !important;
            background: #fff !important;
            padding: 8px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(75, 73, 172, 0.06);
        }

        /* keep disabled selects looking like plain text but still readable */
        .select-wrapper select:disabled,
        input[type="date"]:disabled {
            border: none;
            background: transparent;
            color: #333;
            pointer-events: none;
        }

        /* focus effects */
        .editable-input:focus,
        .client-select.input-editing:focus,
        .project-input.input-editing:focus,
        .service-input.input-editing:focus {
            outline: none;
            border-color: #4b49ac;
            box-shadow: 0 0 0 4px rgba(75, 73, 172, 0.08);
        }

        /* ensure the existing display-box still looks good when not editing */
        .editable-box {
            background: #f9fafc;
            border: 1px solid #eee;
            padding: 10px 12px;
            border-radius: 8px;
            min-height: 40px;
        }
    </style>
@endsection