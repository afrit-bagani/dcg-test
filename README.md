
```
dcg_education
├─ .editorconfig
├─ .nvmrc
├─ app
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Admin
│  │  │  │  ├─ BatchController.php
│  │  │  │  ├─ CourseController.php
│  │  │  │  ├─ ProgrammeController.php
│  │  │  │  ├─ StudentController.php
│  │  │  │  └─ SubjectController.php
│  │  │  ├─ AdminDashboardController.php
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  └─ RegisteredUserController.php
│  │  │  └─ Controller.php
│  │  └─ Requests
│  │     ├─ Course
│  │     │  ├─ StoreCourseRequest.php
│  │     │  └─ UpdateCourseRequest.php
│  │     ├─ Student
│  │     │  ├─ Personal
│  │     │  │  └─ UpdatePersonalRequest.php
│  │     │  ├─ StoreStudentRequest.php
│  │     │  └─ UpdateStudentRequest.php
│  │     ├─ Subject
│  │     │  ├─ StoreSubjectRequest.php
│  │     │  └─ UpdateSubjectRequest.php
│  │     └─ UpdateBulkStatusRequest.php
│  ├─ Models
│  │  ├─ Batch.php
│  │  ├─ Course.php
│  │  ├─ Programme.php
│  │  ├─ Student.php
│  │  ├─ Subject.php
│  │  └─ User.php
│  └─ Providers
│     └─ AppServiceProvider.php
├─ ARCHITECTURE.md
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ sanctum.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  ├─ BatchFactory.php
│  │  ├─ CourseFactory.php
│  │  ├─ StudentFactory.php
│  │  ├─ SubjectFactory.php
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  └─ 2026_02_26_070725_create_ums_tables.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ lang
│  └─ en
│     ├─ auth.php
│     ├─ pagination.php
│     ├─ passwords.php
│     └─ validation.php
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ admin
│     │  ├─ dashboard.blade.php
│     │  ├─ modules
│     │  │  ├─ batch.blade.php
│     │  │  ├─ course.blade.php
│     │  │  ├─ programme.blade.php
│     │  │  ├─ student.blade.php
│     │  │  └─ subject.blade.php
│     │  ├─ partials
│     │  │  └─ sidebar.blade.php
│     │  └─ students
│     │     ├─ education.blade.php
│     │     ├─ index.blade.php
│     │     ├─ personal.blade.php
│     │     └─ _sidebar.blade.php
│     ├─ auth
│     │  ├─ login.blade.php
│     │  └─ register.blade.php
│     ├─ components
│     │  └─ admin-layout.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ api.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ certs
│  │  │  └─ isrgrootx1.pem
│  │  ├─ private
│  │  └─ public
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 012a5e348b34199d5121bb00a7a20e5c.php
│  │     ├─ 14ba80ce7088479a56d6a5c12578bc6c.php
│  │     ├─ 1c5d973aa67ee3ea3fed5bb3aed7454e.php
│  │     ├─ 1f5b6692da9d854156e79760de170398.php
│  │     ├─ 2345e024c02c4d4d4c46e0a01c5b1210.php
│  │     ├─ 2de5a0c025e3034ef3797080eea34a07.php
│  │     ├─ 3d2843101118dfa6aa0d1ce780991ef9.php
│  │     ├─ 3d6dbfbdc8b9cecf68956c71bc078e18.php
│  │     ├─ 3e62377414cc8475cfb9de92fa04905f.php
│  │     ├─ 41f2d762c7a0e6b8add5686a94da57f7.php
│  │     ├─ 4fb654d768d278a919619c17dfe454ad.php
│  │     ├─ 5040562baedd8357e8e5b99691be71c2.php
│  │     ├─ 50a928f798bf336a5680bb1318b0b4aa.php
│  │     ├─ 54d98de244c1210704f4e482ef2a494e.php
│  │     ├─ 59cdcc14c2daf9615a62bd6f9237c1b1.php
│  │     ├─ 5f0972487e2787a39a66bf8c756417dc.php
│  │     ├─ 61b7034ecdd0ffd8fcfe1d733308f83c.php
│  │     ├─ 62da0f1ea663327bf643b6692d778265.php
│  │     ├─ 6af55654c69e195ed34ad8a2ab555dea.php
│  │     ├─ 73b7b00a99e80b29d62b602d55080e44.php
│  │     ├─ 7668cc6014588716fd01b9f152fed433.php
│  │     ├─ 780a2615fd602133d561836bb0345bdc.php
│  │     ├─ 782cb1cade7990ae1ce8ba3109ead39c.php
│  │     ├─ 7eba18a01d7d4aeb0ae961e7cd6e2b08.php
│  │     ├─ 807830d45e4362959394159e3a1d9e8e.php
│  │     ├─ 8233f13e01f5e9857c57783b26578839.php
│  │     ├─ 86a43b86adf4781d5dfe2503140ac9f1.php
│  │     ├─ 87b10c0fba7ac2e2dd8d40f8d2f825e1.php
│  │     ├─ 8f90f9365e445eae0e5222552c1b01f2.php
│  │     ├─ 990ce4c95eafaa51606b0fd4be7c68ca.php
│  │     ├─ 9e1f951095c29722c1c8c172d8f4c97e.php
│  │     ├─ a6d27bc21b0c8525f95d54bdd2dbb26c.php
│  │     ├─ a7f984048ff0e27a106e8ef59d3ae65c.php
│  │     ├─ a9084159e4fbbff412363a3b0b19f7fc.php
│  │     ├─ aa0bd6e9463482451717d459b03df750.php
│  │     ├─ afb592b20375e5afcee3e83b71f18311.php
│  │     ├─ b152ab8024096872deb82116563d4662.php
│  │     ├─ b2afb61b2485de4195428d2d35aafc41.php
│  │     ├─ b6f0af845ffcbcce2590511ae7f08b5d.php
│  │     ├─ bb9951204fa4b7883e1861ccda32e99a.php
│  │     ├─ c0f88495ac943e4fe87f46cbf6f2d10f.php
│  │     ├─ c2fa834bc18ac92a72722f0728b94d0f.php
│  │     ├─ c3ea6543aafd517e68dae3a769ba62ef.php
│  │     ├─ c4bdac60c56a9954161a5ab5dbc11f21.php
│  │     ├─ c51d1a83cb1dfd812381b8c2d164ff17.php
│  │     ├─ c86f630ad3bee5cc3f3eb20b7c587297.php
│  │     ├─ ca26cc98a608175c7bb9df1bf3e352af.php
│  │     ├─ ce07bf6708796057dc49032103cff428.php
│  │     ├─ ceb20402a96b8cc05fc3569824217bf1.php
│  │     ├─ d1e816850e382931360a06c2d2e4e080.php
│  │     ├─ d77f0ec726bea67b11482679e8688a77.php
│  │     ├─ d9394e5d48ffa73d688e7f307fa8ff13.php
│  │     ├─ e4ade8db85f966ebbc074c17e71c963c.php
│  │     ├─ e67c18a883c9097c5c7d7c7ea0649d58.php
│  │     ├─ ec7b48875c8620e416be410a8974f5b6.php
│  │     ├─ f18ba0e147a5e14e128e04db81a1bec6.php
│  │     ├─ f8018261487bf5e88b932c800b426a79.php
│  │     ├─ f89dd4366b0da21c74a3f71b88ed13be.php
│  │     ├─ fbb49e0a63750f273ed684770d3131c7.php
│  │     └─ fe498df7910692a70fbd6442375eb2a6.php
│  └─ logs
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ Pest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
├─ vercel.json
└─ vite.config.js

```