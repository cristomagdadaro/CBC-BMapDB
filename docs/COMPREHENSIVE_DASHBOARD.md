# Comprehensive Dashboard Implementation

## Overview
This document describes the comprehensive dashboard system implemented for the CBC BMapDB application, supporting multiple user roles: Admin, Breeder, Focal Person, and Researcher.

## Features Implemented

### 1. Backend Components

#### DashboardController (`app/Http/Controllers/DashboardController.php`)
A comprehensive controller that provides:
- **Role-based statistics** for all user types
- **Real-time activity tracking** with online user detection
- **Recent activities feed** showing latest changes across both modules
- **System overview** with user role distribution and module statistics
- **User activity tracking endpoint** for keeping sessions active

Key Methods:
- `index()` - Main dashboard data provider
- `getStatistics()` - General statistics for all users
- `getRecentActivities()` - Combined activities from PbMap and TWG DB
- `getOnlineUsers()` - Users active in the last 5 minutes (Admin only)
- `getSystemOverview()` - System-wide metrics (Admin only)
- `getBreederStats()` - Breeder-specific data
- `getFocalPersonStats()` - Focal person-specific data
- `getResearcherStats()` - Researcher-specific data
- `updateActivity()` - Endpoint to track user activity

#### TrackUserActivity Middleware (`app/Http/Middleware/TrackUserActivity.php`)
Automatically tracks user activity on every web request by updating the `last_activity_at` timestamp.
- Updates only every 1 minute to reduce database writes
- Registered in the web middleware group

#### Database Migration
- Added `last_activity_at` column to users table to track online status
- Migration file: `2025_10_07_000001_add_last_activity_to_users_table.php`

### 2. Frontend Components

#### Vue Components Created

**StatisticsCard.vue** (`resources/js/Pages/Dashboard/components/StatisticsCard.vue`)
- Reusable card component for displaying statistics
- Features gradient backgrounds, icons, and optional trend indicators
- Props: title, value, subtitle, icon, bgColor, trend

**OnlineUsersWidget.vue** (`resources/js/Pages/Dashboard/components/OnlineUsersWidget.vue`)
- Displays currently online users (Admin only)
- Shows user profile photos, names, roles, and last activity time
- Real-time online indicator badge
- Scrollable list for many users

**RecentActivitiesWidget.vue** (`resources/js/Pages/Dashboard/components/RecentActivitiesWidget.vue`)
- Shows recent activities from both PbMap and TWG DB modules
- Color-coded by activity type (commodities, projects, breeders, etc.)
- Displays who made changes and when
- Refresh functionality
- Icons for different activity types

**SystemOverviewWidget.vue** (`resources/js/Pages/Dashboard/components/SystemOverviewWidget.vue`)
- Admin-only comprehensive system statistics
- User role distribution with progress bars
- Total users, active users, recent registrations
- Module-specific statistics for PbMap and TWG DB
- Visual representation of data

**QuickActionsWidget.vue** (`resources/js/Pages/Dashboard/components/QuickActionsWidget.vue`)
- Role-based quick action buttons
- Provides shortcuts to common tasks
- Actions filtered by user role permissions
- Includes: Add Commodity, Add Project, View Reports, Manage Users, View Map, Browse Database

#### Enhanced Dashboard.vue (`resources/js/Pages/Dashboard.vue`)
The main dashboard page now includes:
- **Statistics overview** with 4 main cards showing totals
- **Role-specific statistics** (e.g., "My Commodities" for breeders)
- **Recent activities feed** in the main content area
- **Quick actions sidebar** for common tasks
- **Admin widgets** for online users and system overview
- **Existing features preserved** (welcome banner, permissions cards, upcoming features)
- **Automatic activity tracking** via JavaScript interval (every 60 seconds)
- **Responsive layout** with 3-column grid on large screens

### 3. Routes & Configuration

#### Web Routes Updated (`routes/web.php`)
- Dashboard route now uses `DashboardController@index`
- Added `/dashboard/activity` POST endpoint for activity tracking
- Imported DashboardController

#### Middleware Registration (`app/Http/Kernel.php`)
- TrackUserActivity middleware added to web middleware group
- Automatically tracks all authenticated users

#### User Model Updated (`app/Models/User.php`)
- Added `last_activity_at` to fillable fields
- Added `last_activity_at` to casts (datetime)

## Dashboard Features by Role

### Admin Dashboard Includes:
✓ Total system statistics (breeders, commodities, experts, projects)
✓ Online users list with real-time status
✓ System overview with user role distribution
✓ Recent activities across all modules
✓ User management quick actions
✓ Complete system visibility

### Breeder Dashboard Includes:
✓ Total system statistics
✓ My Commodities count
✓ Recent commodities I've added
✓ Quick actions to add commodities
✓ View map and browse database

### Focal Person Dashboard Includes:
✓ Total system statistics
✓ Pending approvals (placeholder)
✓ Institution statistics
✓ Quick actions for both modules
✓ Reports access

### Researcher Dashboard Includes:
✓ Total system statistics
✓ Available commodities and projects
✓ My projects count
✓ Quick actions to add projects
✓ Browse both databases

## Activity Tracking System

### How It Works:
1. **Middleware tracking**: Every web request updates user's `last_activity_at`
2. **Frontend tracking**: JavaScript sends activity ping every 60 seconds
3. **Online detection**: Users active within last 5 minutes are considered "online"
4. **Efficient updates**: Database only updates if more than 1 minute has passed

### Recent Activities:
- Tracks creates and updates for:
  - Commodities (Plant Breeders Map)
  - TWG Projects (TWG Biotech Database)
  - Breeders (Plant Breeders Map)
- Shows who made the change, when, and what module
- Color-coded by type for easy identification
- Latest 15 activities displayed

## Visual Design

### Color Scheme:
- **Green**: Breeders/Commodities (Plant Breeders Map)
- **Blue**: TWG Projects/Database
- **Purple**: TWG Experts
- **Indigo**: Projects/System features
- **Red**: Admin features
- **Yellow**: Focal Person features

### Layout:
- Mobile-first responsive design
- Cards with shadows and hover effects
- Gradient backgrounds for statistics
- Icons from Font Awesome
- Smooth transitions and animations

## Database Schema Changes

```sql
ALTER TABLE users ADD COLUMN last_activity_at TIMESTAMP NULL AFTER email_verified_at;
```

## API Endpoints

### GET /dashboard
Returns comprehensive dashboard data including:
- statistics
- recentActivities
- onlineUsers (admin only)
- systemOverview (admin only)
- breederStats (if user is breeder)
- focalPersonStats (if user is focal person)
- researcherStats (if user is researcher)

### POST /dashboard/activity
Updates the authenticated user's last_activity_at timestamp.
Returns: `{"success": true}`

## Future Enhancements

Potential improvements mentioned in the dashboard:
1. Executive Dashboards - Comprehensive summaries
2. API Service - Real-time data access for external systems
3. Chat Room - Built-in messaging
4. Data View Customization - User preferences

## Testing Recommendations

1. Test with different user roles to verify role-specific content
2. Verify online users appear within 5 minutes of activity
3. Check recent activities update correctly after data changes
4. Test responsive design on mobile, tablet, and desktop
5. Verify activity tracking doesn't impact performance
6. Test quick actions navigate to correct pages

## Performance Considerations

- Activity tracking throttled to 1-minute intervals in middleware
- Frontend pings every 60 seconds (not on every action)
- Recent activities limited to 15 items
- Online users query only checks last 5 minutes
- Proper database indexing recommended on `last_activity_at` column

## Maintenance Notes

- Monitor database performance on users table with activity tracking
- Consider archiving old activity data if needed
- Adjust online threshold (currently 5 minutes) based on requirements
- Update statistics calculations as new modules are added

