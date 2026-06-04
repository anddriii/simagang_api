# Ringkasan Endpoint SIMAGANG API

## Auth
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/profile`
- `PUT /api/auth/profile`
- `PUT /api/auth/change-password`

## Dashboard
- `GET /api/student/dashboard`
- `GET /api/lecturer/dashboard`
- `GET /api/field-supervisor/dashboard`
- `GET /api/admin/dashboard`

## Master Data
- `apiResource /api/students`
- `apiResource /api/lecturers`
- `apiResource /api/field-supervisors`
- `apiResource /api/companies`
- `apiResource /api/internship-periods`

## Internship
- `GET /api/internship-applications`
- `POST /api/internship-applications`
- `GET /api/internship-applications/{id}`
- `POST /api/internship-applications/{id}/documents`
- `PUT /api/internship-applications/{id}/approve`
- `PUT /api/internship-applications/{id}/reject`
- `GET /api/internship-assignments`
- `GET /api/internship-assignments/{id}`

## Logbook
- `apiResource /api/logbooks`
- `POST /api/logbooks/{id}/attachments`
- `POST /api/logbooks/{id}/submit`
- `GET /api/field-supervisor/logbooks/pending`
- `PUT /api/logbooks/{id}/approve`
- `PUT /api/logbooks/{id}/revise`
- `PUT /api/logbooks/{id}/reject`

## Monitoring & Warning
- `GET /api/monitoring/students/{student_id}/progress`
- `GET /api/lecturer/monitoring`
- `GET /api/warnings`
- `POST /api/warnings/generate`
- `PUT /api/warnings/{id}/resolve`

## Consultation
- `GET /api/consultations`
- `POST /api/consultations`
- `GET /api/consultations/{id}`
- `POST /api/consultations/{id}/reply`
- `PUT /api/consultations/{id}/close`

## Assessment
- `GET /api/assessments`
- `POST /api/assessments`
- `GET /api/assessments/{id}`
- `PUT /api/assessments/{id}`

## Notification
- `GET /api/notifications`
- `PUT /api/notifications/{id}/read`
- `PUT /api/notifications/read-all`

## Chatbot
- `POST /api/chatbot/ask`
- `GET /api/chatbot/history`
- `GET /api/chatbot/conversations/{id}`
- `apiResource /api/chatbot/knowledge-bases`

## Documents
- `GET /api/documents`
- `POST /api/documents`
- `DELETE /api/documents/{id}`
