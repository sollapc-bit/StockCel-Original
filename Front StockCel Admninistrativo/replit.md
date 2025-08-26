# replit.md

## Overview

SoftwarePar is a complete license management system with role-based access control, featuring admin and reseller dashboards. The application has been fully converted to PHP/MySQL for traditional web hosting compatibility, with an automated browser-based installer for seamless deployment. The system includes a professional landing page, comprehensive management panels, and user-friendly installation process.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Frontend Architecture
- **Language**: PHP 7.4+ with modern syntax
- **Framework**: Pure PHP with Bootstrap 5 for styling
- **UI Components**: Font Awesome icons and custom CSS
- **Responsive Design**: Mobile-first approach with CSS Grid/Flexbox
- **Forms**: Native HTML5 validation with PHP processing
- **Assets**: Integrated PNG logos and professional design

### Backend Architecture
- **Language**: PHP 7.4+ with PDO for database access
- **Authentication**: PHP sessions with bcrypt password hashing
- **Database**: MySQL 5.7+ with optimized queries and indexing
- **Security**: Apache .htaccess configuration and input validation
- **Architecture**: MVC pattern with separation of concerns

### Installation System
- **Web Installer**: Browser-based 5-step installation wizard
- **Auto-Configuration**: Automatic database creation and setup
- **Requirements Check**: Real-time validation of server capabilities
- **Security**: Automatic installer lockdown after completion
- **User Setup**: Custom administrator account creation

## Key Components

### Authentication System
- Session-based authentication using express-session
- Role-based access control (admin/revendedor)
- Password hashing with bcrypt
- Protected routes with middleware validation

### User Management
- Three main entities: Users (admin/revendedor), Licenses, and Final Clients
- Hierarchical relationship: Users -> Licenses -> Final Clients
- CRUD operations for all entities with proper permissions

### UI Components
- Comprehensive shadcn/ui component library
- Custom logo and branding components
- Responsive design with mobile-first approach
- Toast notifications for user feedback

## Data Flow

### User Authentication Flow
1. User submits login credentials
2. Server validates against database using bcrypt
3. Session is created and stored
4. Frontend receives user data and updates auth state
5. Protected routes check session validity

### License Management Flow
1. Admin creates licenses and assigns to revendedores
2. Revendedores can view their assigned licenses
3. Revendedores can create final clients under their licenses
4. Admin has full visibility across all licenses and clients

### Data Persistence
- All data operations go through Drizzle ORM
- Database queries use connection pooling
- Proper error handling and validation at all levels

## External Dependencies

### Core Dependencies
- **@neondatabase/serverless**: PostgreSQL connection for Neon
- **drizzle-orm**: Type-safe database operations
- **express**: Web framework
- **bcrypt**: Password hashing
- **express-session**: Session management
- **@tanstack/react-query**: Server state management
- **react-hook-form**: Form handling
- **zod**: Schema validation

### UI Dependencies
- **@radix-ui/***: Headless UI components
- **tailwindcss**: Utility-first CSS framework
- **lucide-react**: Icon library
- **wouter**: Lightweight routing

## Deployment Strategy

### Development Setup
- Uses Vite dev server with HMR
- TypeScript compilation with strict mode
- Development-specific middleware and logging

### Production Build
- Frontend builds to `dist/public` directory
- Backend bundles with esbuild to `dist/index.js`
- Static files served from Express in production
- Environment-based configuration

### Hosting Requirements
- PHP 7.4+ with PDO and PDO_MySQL extensions
- MySQL 5.7+ database
- Apache web server with .htaccess support
- Standard shared hosting compatibility

### Configuration
- Database connection through `config/database.php`
- Automatic configuration via web installer
- Environment detection and error handling
- Production-ready security settings

### Recent Changes (January 2025)
- ✅ **Complete PHP conversion** from React/Node.js stack
- ✅ **Browser-based installer** with 5-step wizard interface
- ✅ **Automatic database setup** and configuration generation
- ✅ **Professional landing page** with company branding
- ✅ **Enhanced login system** with demo user auto-fill
- ✅ **Security hardening** with Apache configuration
- ✅ **Logo integration** with PNG assets throughout system
- ✅ **WhatsApp integration** with floating contact button
- ✅ **Installation lockdown** preventing unauthorized reinstalls

The application is now fully compatible with basic shared hosting providers (cPanel, DirectAdmin) and requires zero technical configuration for end users.