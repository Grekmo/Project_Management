<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Report</title>

    <style>
        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #334155;
            line-height: 1.6;
        }

        * {
            box-sizing: border-box;
        }

        /* HEADER */
        .header {
            background-color: #20364D;
            color: white;
            padding: 25px;
            margin-bottom: 25px;
        }

        .header-label {
            color: #B8C9D8;
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .header h1 {
            color: white;
            font-size: 25px;
            margin: 10px 0;
        }

        .header-date {
            color: #D5E0E9;
            font-size: 10px;
        }

        .report-tag {
            display: inline-block;
            background-color: #36536F;
            color: white;
            padding: 5px 10px;
            font-size: 9px;
            letter-spacing: 1px;
        }

        /* SECTIONS */
        .section {
            margin-top: 23px;
        }

        .section-title {
            color: #20364D;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #E2E8F0;
            padding-bottom: 7px;
            margin-bottom: 14px;
        }

        /* PROJECT INFO */
        .info-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-left: -8px;
        }

        .info-box {
            background-color: #F1F5F9;
            padding: 12px;
            width: 50%;
        }

        .info-label {
            color: #64748B;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            color: #20364D;
            font-size: 11px;
            font-weight: bold;
        }

        .description {
            background-color: #F8FAFC;
            border-left: 3px solid #36536F;
            padding: 12px;
            margin-top: 10px;
        }

        /* MANAGER */
        .manager-box {
            background-color: #F1F5F9;
            padding: 13px;
        }

        .manager-name {
            color: #20364D;
            font-size: 12px;
            font-weight: bold;
        }

        .manager-email {
            color: #64748B;
            font-size: 10px;
        }

        /* TABLES */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #20364D;
            color: white;
            font-size: 9px;
            text-align: left;
            padding: 10px 8px;
            text-transform: uppercase;
        }

        .data-table td {
            border-bottom: 1px solid #E2E8F0;
            padding: 9px 8px;
            font-size: 10px;
        }

        .data-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        .empty {
            color: #94A3B8;
            font-style: italic;
            padding: 12px;
            background-color: #F8FAFC;
        }

        /* STATUS */
        .status {
            display: inline-block;
            padding: 3px 7px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #DCFCE7;
            color: #166534;
        }

        .status-in-progress {
            background-color: #DBEAFE;
            color: #1D4ED8;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-other {
            background-color: #E2E8F0;
            color: #475569;
        }

        /* FOOTER */
        .footer {
            border-top: 1px solid #E2E8F0;
            margin-top: 35px;
            padding-top: 10px;
            color: #94A3B8;
            font-size: 8px;
            text-align: center;
        }

        .data-table tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-label">
            Project Management System
        </div>
        <h1>{{ $project->name }}</h1>
        <div class="header-date">
            PROJECT REPORT |
            Generated on {{ now()->format('d/m/Y') }}
        </div>
        <br>
        <span class="report-tag">PROJECT DETAILS</span>
    </div>


    <!-- PROJECT INFORMATION -->
    <div class="section">
        <div class="section-title">
            01. Project Information
        </div>
        <table class="info-grid">
            <tr>
                <td class="info-box">
                    <div class="info-label">Project Status</div>
                    <div class="info-value">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </div>
                </td>

                <td class="info-box">
                    <div class="info-label">Project Manager</div>

                    <div class="info-value">
                        {{ $project->manager?->name ?? 'Not assigned' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="info-box">
                    <div class="info-label">Start Date</div>
                    <div class="info-value">
                        {{ $project->start_date ?? 'Not specified' }}
                    </div>
                </td>

                <td class="info-box">
                    <div class="info-label">End Date</div>
                    <div class="info-value">
                        {{ $project->end_date ?? 'Not specified' }}
                    </div>
                </td>
            </tr>
        </table>
        <div class="description">
            <div class="info-label">Project Description</div>

            {{ $project->description ?? 'No description available.' }}
        </div>
    </div>


    <!-- MANAGER INFORMATION -->
    <div class="section">
        <div class="section-title">
            02. Manager Information
        </div>
        @if($project->manager)
            <div class="manager-box">
                <div class="manager-name">
                    {{ $project->manager->name }}
                </div>
                <div class="manager-email">
                    {{ $project->manager->email }}
                </div>
            </div>
        @else
            <div class="empty">
                No manager assigned to this project.
            </div>
        @endif
    </div>

    <!-- EMPLOYEES -->
    <div class="section">
        <div class="section-title">
            03. Project Team
        </div>
        @if($project->employees->count() > 0)

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="40%">Employee Name</th>
                        <th width="50%">Email Address</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($project->employees as $index => $employee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                No employees assigned to this project.
            </div>
        @endif
    </div>

    <!-- TASKS -->
    <div class="section">
        <div class="section-title">
            04. Project Tasks
        </div>
        @if($project->tasks->count() > 0)

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="6%">#</th>
                        <th width="27%">Task Name</th>
                        <th width="20%">Status</th>
                        <th width="27%">Assigned Employee</th>
                        <th width="20%">End Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($project->tasks as $index => $task)
                        @php
                            $statusClass = match($task->status) {
                                'completed' => 'status-completed',
                                'in_progress' => 'status-in-progress',
                                'pending' => 'status-pending',
                                default => 'status-other',
                            };
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $task->name }}</td>
                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                {{ $task->employee?->name ?? 'Not assigned' }}
                            </td>
                            <td>
                                {{ $task->end_date ?? 'Not specified' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                No tasks available for this project.
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer">
        PROJECT MANAGEMENT SYSTEM
        &nbsp; | &nbsp;
        CONFIDENTIAL PROJECT REPORT
    </div>
</body>
</html>
