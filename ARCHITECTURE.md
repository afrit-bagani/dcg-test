# University Management System (UMS) - Architecture & Context

## Tech Stack

- Backend: Laravel (PHP)

- Frontend: Blade Templates, Tailwind CSS, (use jquery later)

- Database: MySQL

- Architecture: MVC (Model-View-Controller) with Strict Resource Controllers.

## Engineering Rules & Standards (Crucial for AI Prompts)

When writing code for this project, the following rules MUST be followed:

Database Interactions (Raw SQL): * We do NOT use Eloquent ORM.

We strictly use `DB::select()`, `DB::insert()`, `DB::update()` with prepared statements (`?` bindings) to prevent SQL injection, or any other DB: mwthod exist

Controller Architecture: * Strict Resource Controllers (Single Responsibility Principle).

E.g., BatchController handles batches. The base /dashboard only redirects to the default module.

Routing: * Strict RESTful plural routes (/dashboard/batches, /dashboard/courses).

No ?tab= query strings for navigation.

View Logic:

Sidebar and UI navigation use request()->routeIs('admin.batch.*') to determine active state.

Modals (Create/Edit/Status) are shared at the bottom of the Blade files. JavaScript dynamically injects the action URL into the form before opening.

Validation:

FormRequests or strict $request->validate() for every incoming request.

Updates must use Rule::unique()->ignore() to prevent collision traps.

🗄️ Database Schema Summary

Note: All tables have created_by, created_at, updated_at.

batch_master: batch_id (PK), code, name, is_active

programme_master: programme_id (PK), code, name, is_active

course_master: course_id (PK), code, name, is_active,

subject_master: subject_id (PK), code, name, internal_full_marks, internal_pass_marks, theory_full_marks, theory_pass_marks, practical_full_marks, practical_pass_marks, is_active

student_registrations: student_id (PK), reg_no, name, email, batch_id (FK), programme_id (FK), course_id (FK), is_active

🚀 Current Progress & Next Steps

[x] Core Admin Layout & Sidebar

[x] Batch Module (Full CRUD)

[x] Programme Module (Full CRUD)

[x] Course Module (Full CRUD), while creating course it has relation, it belong to programme

[x] Subject Module (Full CRUD + Marks) -> it has relation, belong to course

[x] Student Module (Registration & Edit) -> it has relation, it belong to batch, programme, course,
