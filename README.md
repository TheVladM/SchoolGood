# SchoolGood - School Management System

A comprehensive Laravel-based school management system for organizing students, classes, courses, payments, library management, and administrative operations.

## 📋 Features

### Core Management
- **Student Management**: Track students across classroom levels (CM1/CM2) and sections (Francophone/Anglophone)
- **Classroom Management**: Organize classes with two-teacher configuration (main teacher + language teacher)
- **Course Management**: Schedule courses by day with teacher assignments
- **User Management**: Role-based access control with 5 user types (Founder, Admin, Scolarite, Teacher, Parent)

### Academic Features
- **Homework System**: Teachers assign homework with due dates, students submit, teachers grade
- **School Years**: Manage academic years with automatic student promotion and archiving
- **Timetable**: Track course schedules organized by day

### Financial Management
- **Payment Tracking**: Multiple payment types (registration, installments) with validation workflow
- **Tuition Fees**: Configurable fees by classroom level and section
  - CM1 Francophone: 185,000 CFA (35K registration + 3 × 50K installments)
  - CM2 Francophone: 200,000 CFA (35K registration + 3 × 55K installments)
  - CM1 Anglophone: 205,000 CFA (40K registration + 3 × 55K installments)
  - CM2 Anglophone: 220,000 CFA (40K registration + 3 × 60K installments)

### Library Management
- **Book Inventory**: Track books with ISBN, categories, languages, and availability
- **Loan System**: Manage book loans with automatic penalty calculations for overdue returns
- **Penalty Tracking**: Late fees calculated per day

### Communications
- **Announcements**: Internal messaging system with founder approval workflow
- **Dashboard**: Role-personalized dashboards showing relevant statistics and activity

### Authorization & Security
- **Policies**: Fine-grained access control via 8 Laravel Policies
- **Founder Protection**: Immutable founder user (cannot be modified or deleted)
- **Two-Teacher Validation**: SIL-CM2 classrooms require two different teachers
- **Role-Based Access**: Founder, Admin, Scolarite, Teacher, Parent with appropriate permissions

## 🚀 Quick Start

### Prerequisites
- PHP 8.3.30+
- MySQL or SQLite
- Composer
- Node.js 18+
- Laragon (for Windows development)

### Installation

1. **Clone and setup**
```bash
cd c:\laragon\www\SchoolGood
composer install
npm install
```

2. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Setup database**
```bash
php artisan migrate
php artisan db:seed
```

4. **Build assets**
```bash
npm run build
# or for development with watch:
npm run dev
```

5. **Start server**
```bash
php artisan serve
# Access at http://127.0.0.1:8000
```

### Test Users (From Seeder)

| Email | Password | Role |
|-------|----------|------|
| founder@schoolgood.test | password | Founder |
| admin@schoolgood.test | password | Admin |
| scolarite@schoolgood.test | password | Scolarite |
| teacher1@schoolgood.test | password | Teacher |
| teacher2@schoolgood.test | password | Teacher |
| parent@schoolgood.test | password | Parent |

## 📁 Project Structure

```
app/
  Enums/              # Enum definitions (UserRole, ClassroomSection, PaymentStatus, etc.)
  Http/
    Controllers/      # Web controllers for CRUD operations
    Controllers/Api/  # RESTful API controllers
    Resources/        # JSON serialization resources for API
    Middleware/       # Request middleware
  Models/             # Eloquent models (User, Student, Course, Payment, etc.)
  Policies/           # Authorization policies (8 total)
  Providers/          # Service providers (AuthServiceProvider registers all policies)

database/
  migrations/         # Database schema migrations (17 total)
  seeders/            # Test data seeders
  factories/          # Model factories for testing

resources/views/      # Blade templates
  homeworks/          # Homework CRUD views
  dashboard.blade.php # Role-personalized dashboard

routes/
  web.php             # Web routes for all resources
  api.php             # RESTful API routes (Sanctum authenticated)

tests/
  Feature/            # Feature tests (controller behavior)
  Unit/               # Unit tests (policies, models)
```

## 🔒 Authorization Matrix


### Policies (8 Total)

| Resource | Founder | Admin | Scolarite | Teacher | Parent |
|----------|---------|-------|-----------|---------|--------|
| User | Manage all | Manage non-founder | None | None | View self |
| Classroom | View all | View all | Manage | Teach | View children |
| Course | View all | View all | Manage | Create/teach own | View |
| Student | View all | View all | Manage | View in class | View own children |
| Payment | Validate | Validate | Create/manage | None | View own |
| Homework | Manage all | Manage all | None | Create/manage own | View children's |
| Announcement | Approve all | Approve | Create | Create | View approved |
| TuitionFee | Manage | View | View | View | View |

### Founder Protection
- Founder cannot be modified by any user
- Founder role immutable
- Founder cannot be soft-deleted
- Enforced via `UserPolicy::update()` and `delete()`

## 🛠️ API Documentation

### Authentication

All API endpoints require Sanctum token authentication:

```bash
Authorization: Bearer {token}
```

### API Endpoints

#### Homeworks
- `GET /api/homeworks` - List homeworks with filtering
- `POST /api/homeworks` - Create homework (teachers only)
- `GET /api/homeworks/{id}` - Get homework details
- `PATCH /api/homeworks/{id}` - Update homework (creator/admin/founder)
- `DELETE /api/homeworks/{id}` - Delete homework (creator/admin/founder)

**Filters:**
- `classroom_id` - Filter by classroom
- `teacher_id` - Filter by teacher
- `status` - Filter by status (assigned/submitted/graded)
- `search` - Search by title
- `per_page` - Pagination (default: 15)

#### Classrooms
- `GET /api/classrooms` - List classrooms
- `GET /api/classrooms/{id}` - Get classroom details

**Filters:**
- `section` - Filter by section (Francophone/Anglophone)
- `level` - Filter by level (CM1/CM2)
- `search` - Search by name
- `per_page` - Pagination (default: 15)

#### Students
- `GET /api/students` - List students with filtering
- `GET /api/students/{id}` - Get student details

**Filters:**
- `classroom_id` - Filter by classroom
- `parent_id` - Filter by parent
- `is_active` - Filter active/inactive
- `search` - Search by first/last name
- `per_page` - Pagination (default: 15)

#### Courses
- `GET /api/courses` - List courses with filtering
- `GET /api/courses/{id}` - Get course details

**Filters:**
- `classroom_id` - Filter by classroom
- `teacher_id` - Filter by teacher
- `day` - Filter by day (Monday-Saturday)
- `search` - Search by title
- `per_page` - Pagination (default: 15)

### JSON Response Format

```json
{
  "data": [
    {
      "id": 1,
      "title": "Math Homework",
      "subject": "Mathematics",
      "status": "assigned",
      "due_date": "2026-05-20T15:00:00Z",
      "classroom": {
        "id": 1,
        "name": "CM1 A",
        "level": "CM1",
        "section": "Francophone"
      },
      "teacher": {
        "id": 4,
        "name": "Mr. Jean",
        "email": "teacher1@schoolgood.test",
        "role": "Teacher"
      }
    }
  ],
  "meta": {
    "total": 15,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1
  }
}
```

## 📊 Database Schema (17 Migrations)

### Core Tables
- `users` - User accounts with roles and departments
- `students` - Student records with parent relationships
- `classrooms` - Classroom definitions with two-teacher configuration
- `courses` - Course schedules with teacher assignments
- `school_years` - Academic year tracking with promotion
- `student_school_year_records` - Historical student enrollment

### Financial Tables
- `payments` - Payment tracking with validation workflow
- `tuition_fees` - Fee rates by level and section

### Academic Tables
- `homeworks` - Teacher assignments with due dates
- `timetable_entries` - Course schedule details
- `books` - Library inventory
- `book_loans` - Loan tracking with penalty calculations

### Communication
- `announcements` - Internal messages with approval workflow

### System
- `cache` - Cache storage
- `jobs` - Background job queue

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
php artisan test --filter=HomeworkPolicyTest
php artisan test tests/Feature/Http/Controllers/HomeworkControllerTest
```

### Test Database
- Uses SQLite in-memory database
- RefreshDatabase trait runs migrations before each test
- Factories generate test data automatically

## 📝 Models & Relationships

### User
- `hasMany` homeworks (as teacher)
- `hasMany` courses (as teacher)
- `hasMany` announcements (as author)
- `hasMany` payments (as validator)
- `hasMany` students (as parent)
- `hasMany` tuitionFees (as manager)

### Classroom
- `belongsTo` User (main_teacher)
- `belongsTo` User (language_teacher)
- `hasMany` students
- `hasMany` courses
- `hasMany` homeworks

### Student
- `belongsTo` User (parent)
- `belongsTo` Classroom
- `hasMany` payments

### Homework
- `belongsTo` User (teacher)
- `belongsTo` Classroom
- `hasManyThrough` Students (via classroom)

### Course
- `belongsTo` User (teacher)
- `belongsTo` Classroom

### Payment
- `belongsTo` Student
- `belongsTo` User (validated_by)

## 🔧 Configuration

### Environment Variables (.env)
```
APP_NAME=SchoolGood
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=SchoolGood
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

### Cache Configuration
```bash
php artisan config:cache
php artisan config:clear
```

## 📱 Frontend

- **Framework**: Laravel Blade templates
- **Styling**: Tailwind CSS (resources/css/app.css)
- **Build Tool**: Vite
- **Responsiveness**: Mobile-first design with media breakpoints

### Key Views
- Homework management (index, create, edit, show)
- Role-personalized dashboard
- Student/classroom/course management
- Payment tracking

## 🚢 Deployment

1. **Build for production**
```bash
npm run build
```

2. **Cache configuration**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Database migrations**
```bash
php artisan migrate --force
```

4. **Background jobs (if configured)**
```bash
php artisan queue:work
```

## 📞 Support

For issues, features, or questions, contact the development team.

## 📄 License

Ce projet est développé par des étudiants de la promo 2027 de cybersécurité de l'école nationale supérieure polytechnique de Yaoundé.

---

**Last Updated**: May 2026
**Laravel Version**: 13
**PHP Version**: 8.3.30
**Database**: MySQL/SQLite
