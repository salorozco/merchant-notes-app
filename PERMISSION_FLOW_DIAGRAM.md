# Permission Flow Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                         BACKEND                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌────────────┐      ┌──────────────┐     ┌──────────────┐ │
│  │   User     │──────│    Role      │─────│  Permission  │ │
│  │  Model     │ M:M  │   Model      │ M:M │    Model     │ │
│  └────────────┘      └──────────────┘     └──────────────┘ │
│        │                                                     │
│        │                                                     │
│        ▼                                                     │
│  ┌────────────────┐                                         │
│  │ UserResource   │  Returns:                               │
│  │                │  - id, name, email                      │
│  │                │  - roles: ['admin']                     │
│  │                │  - permissions: ['create-notes', ...]   │
│  └────────────────┘                                         │
│        │                                                     │
│        ▼                                                     │
│  ┌────────────────┐                                         │
│  │AuthController  │                                         │
│  │  /login        │  Returns UserResource                   │
│  │  /user         │                                         │
│  └────────────────┘                                         │
│        │                                                     │
└────────┼─────────────────────────────────────────────────────┘
         │
         │ API Response with roles & permissions
         │
         ▼
┌─────────────────────────────────────────────────────────────┐
│                         FRONTEND                             │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌────────────────┐                                         │
│  │  Auth Store    │  Stores:                                │
│  │  (Pinia)       │  - user object                          │
│  │                │  - roles array                          │
│  │                │  - permissions array                    │
│  └────────────────┘                                         │
│         │                                                    │
│         │ Used by                                            │
│         │                                                    │
│         ▼                                                    │
│  ┌────────────────────────────────────────────┐            │
│  │        Permission Checking Layer           │            │
│  ├────────────────────────────────────────────┤            │
│  │                                             │            │
│  │  usePermissions()  │  Directives           │            │
│  │  Composable        │  - v-permission       │            │
│  │                    │  - v-role             │            │
│  │                                             │            │
│  └────────────────────────────────────────────┘            │
│         │                      │                             │
│         │                      │                             │
│         ▼                      ▼                             │
│  ┌──────────────┐      ┌──────────────┐                    │
│  │ Components   │      │  Templates   │                    │
│  │              │      │              │                    │
│  │ - setup()    │      │ v-if="can"   │                    │
│  │ - methods()  │      │ v-permission │                    │
│  │              │      │              │                    │
│  └──────────────┘      └──────────────┘                    │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

## Permission Check Flow

```
User Action (e.g., Click "Add Note")
    │
    ▼
┌───────────────────────────────────┐
│ Component checks permission       │
│ using usePermissions() composable │
└───────────────────────────────────┘
    │
    ▼
┌───────────────────────────────────┐
│ hasPermission('create-notes')     │
│ checks authStore.user.permissions │
└───────────────────────────────────┘
    │
    ├─── Yes ──▶ Show button / Allow action
    │
    └─── No ───▶ Hide button / Block action
```

## Example: Note Creation Flow

```
1. User logs in
   └─▶ Backend sends user with roles & permissions
       └─▶ authStore stores data

2. User navigates to MerchantShow page
   └─▶ Component setup() calls usePermissions()
       └─▶ canCreateNotes computed property is created
           └─▶ Checks if 'create-notes' in user.permissions

3. Template renders
   └─▶ <button v-if="canCreateNotes">
       ├─── If TRUE: Button is visible
       └─── If FALSE: Button is hidden

4. User clicks "Add Note"
   └─▶ submitNote() method called
       └─▶ (Optional) Additional permission check
           └─▶ Proceeds if authorized
```

## UI Changes Based on Roles

```
┌─────────────────────────────────────────────────────────┐
│                      User Role                           │
├─────────────────────────────────────────────────────────┤
│ Permissions: view-merchants, view-notes, create-notes   │
│                                                          │
│ ┌──────────────────────────────────────────┐            │
│ │          Merchant Notes Page             │            │
│ ├──────────────────────────────────────────┤            │
│ │                                           │            │
│ │  Merchant: ABC Corp                       │            │
│ │                                           │            │
│ │  Notes                [Add Note] ✓        │ ◄─ Visible│
│ │                                           │            │
│ │  ┌─────────────────────────────────┐     │            │
│ │  │ Note body text...               │     │            │
│ │  │ Created: 2024-01-01             │     │            │
│ │  │ [Edit] ✗  [Delete] ✗            │ ◄─ Hidden       │
│ │  └─────────────────────────────────┘     │            │
│ │                                           │            │
│ └──────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                     Manager Role                         │
├─────────────────────────────────────────────────────────┤
│ Permissions: view-*, create-*, update-* (no delete)     │
│                                                          │
│ ┌──────────────────────────────────────────┐            │
│ │          Merchant Notes Page             │            │
│ ├──────────────────────────────────────────┤            │
│ │                                           │            │
│ │  Merchant: ABC Corp                       │            │
│ │                                           │            │
│ │  Notes                [Add Note] ✓        │ ◄─ Visible│
│ │                                           │            │
│ │  ┌─────────────────────────────────┐     │            │
│ │  │ Note body text...               │     │            │
│ │  │ Created: 2024-01-01             │     │            │
│ │  │ [Edit] ✓  [Delete] ✗            │ ◄─ Delete hidden│
│ │  └─────────────────────────────────┘     │            │
│ │                                           │            │
│ └──────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                     Admin Role                           │
├─────────────────────────────────────────────────────────┤
│ Permissions: ALL (view-*, create-*, update-*, delete-*) │
│                                                          │
│ ┌──────────────────────────────────────────┐            │
│ │          Merchant Notes Page             │            │
│ ├──────────────────────────────────────────┤            │
│ │                                           │            │
│ │  Merchant: ABC Corp                       │            │
│ │                                           │            │
│ │  Notes                [Add Note] ✓        │ ◄─ Visible│
│ │                                           │            │
│ │  ┌─────────────────────────────────┐     │            │
│ │  │ Note body text...               │     │            │
│ │  │ Created: 2024-01-01             │     │            │
│ │  │ [Edit] ✓  [Delete] ✓            │ ◄─ All visible  │
│ │  └─────────────────────────────────┘     │            │
│ │                                           │            │
│ └──────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────┘
```

## Code Implementation Map

```
frontend/
├── src/
│   ├── composables/
│   │   └── usePermissions.js          ◄─ Core permission logic
│   │
│   ├── directives/
│   │   └── permissions.js             ◄─ v-permission, v-role
│   │
│   ├── stores/
│   │   └── authStore.js               ◄─ Enhanced with permissions
│   │
│   ├── views/
│   │   └── MerchantShow.vue           ◄─ Uses canCreateNotes
│   │
│   ├── components/
│   │   └── notes/
│   │       └── NoteItem.vue           ◄─ Uses canUpdate/DeleteNotes
│   │
│   └── main.js                        ◄─ Registers directives
│
├── PERMISSIONS_README.md              ◄─ Complete guide
└── PERMISSION_EXAMPLES.md             ◄─ Code examples

backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AuthController.php     ◄─ Returns UserResource
│   │   │
│   │   └── Resources/
│   │       └── UserResource.php       ◄─ Includes roles & permissions
│   │
│   ├── Models/
│   │   ├── User.php                   ◄─ hasPermission() method
│   │   ├── Role.php
│   │   └── Permission.php
│   │
│   └── Policies/
│       ├── MerchantPolicy.php
│       └── NotePolicy.php
│
└── database/
    └── seeders/
        └── RolesAndPermissionsSeeder.php
```

## Integration Points

```
┌──────────────┐
│   Login      │
│   Page       │
└──────┬───────┘
       │
       │ 1. User enters credentials
       ▼
┌──────────────┐
│ AuthService  │
│  .login()    │
└──────┬───────┘
       │
       │ 2. POST /api/login
       ▼
┌──────────────┐
│ AuthController
│  .login()    │
└──────┬───────┘
       │
       │ 3. Returns UserResource with roles & permissions
       ▼
┌──────────────┐
│  authStore   │
│  .login()    │
└──────┬───────┘
       │
       │ 4. Stores user data with permissions
       ▼
┌──────────────┐
│ All Components
│ Can now check
│  permissions │
└──────────────┘
```

## Summary

The permission system provides:
1. **Backend**: Returns roles and permissions with user data
2. **Frontend Storage**: authStore holds permission data
3. **Permission Checking**: Composable and directives for easy checks
4. **UI Adaptation**: Components show/hide based on permissions
5. **Type Safety**: Consistent permission names across stack
