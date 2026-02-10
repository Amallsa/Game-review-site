# Mobile App Prototype Plan (Hajj/Umrah Navigation)

## 1) Recommended Technology Stack

### Cross-platform framework (iOS + Android)
**React Native with Expo (TypeScript)**

Why this is the best fit for your scope:
- Single codebase for iOS and Android.
- Fast prototype delivery using Expo tools and over-the-air updates.
- Strong ecosystem for maps, localization, notifications, and authentication.
- Easy integration with Supabase for authentication and backend services.

For AR capability:
- Start prototype with **camera + directional overlays + indoor map guidance**.
- For full production-grade AR wayfinding, integrate:
  - iOS: ARKit (via native module)
  - Android: ARCore (via native module)
- Practical approach: build MVP navigation first, then add advanced AR module.

### Backend and database environment
**Supabase**
- PostgreSQL database
- Supabase Auth (email/OTP/social login)
- Row-Level Security (RLS) for secure multi-user access
- Realtime channels for group tracking updates
- Storage for profile pictures and optional media
- Edge Functions for secure server-side logic (alerts, translations orchestration)

Recommended data model (minimum):
- `users` (profile + language + role)
- `groups` (group metadata)
- `group_members` (membership and permissions)
- `locations` (gates, clinics, security points, landmarks)
- `routes` (indoor paths/segments)
- `live_positions` (user location updates)
- `emergency_alerts` (SOS events + status)
- `translations_cache` (optional cache to reduce API cost)

### IDE and development tooling
- **Primary IDE**: Visual Studio Code
- Extensions: ESLint, Prettier, React Native Tools, GitLens, SQLTools
- API testing: Postman/Insomnia
- Design/prototyping: Figma
- Version control: Git + GitHub
- Project management: Jira or Trello

### Build and release environment
- **Expo Application Services (EAS)**
  - EAS Build for iOS/Android binaries
  - EAS Submit for App Store / Play Store submissions
- CI/CD: GitHub Actions
  - Lint + tests on pull requests
  - Build preview artifacts on main branch

---

## 2) Proposed System Modules

1. **Authentication & User Profile**
   - Sign up / login / OTP
   - Preferred language selection
   - Emergency contact setup

2. **Indoor Navigation (MVP)**
   - Search destination (gate, clinic, prayer area, security point)
   - Step-by-step indoor route guidance
   - Optional camera overlay direction indicators

3. **AR Navigation (Phase 2)**
   - Live directional arrows using ARKit/ARCore
   - Visual anchors for landmarks and route checkpoints

4. **Real-Time Translation**
   - Phrase translation for pilgrim-to-staff communication
   - Quick templates for common needs (“Need medical help”, “Where is Gate X?”)

5. **Group Tracking**
   - Create/join group
   - Live location sharing (consent-based)
   - Distance and separation alerts

6. **Emergency Assistance**
   - One-tap SOS
   - Route to nearest clinic/security point
   - Emergency contact notification flow

---

## 3) Non-Functional Requirements

- **Performance**: location updates in near real-time (2–5 seconds in active mode)
- **Scalability**: Supabase Postgres indexing + Realtime channel partitioning by group
- **Security**: RLS, JWT-based auth, encrypted transport (HTTPS/TLS)
- **Privacy**: explicit consent for location sharing, retention policy for live positions
- **Reliability**: offline fallback for cached maps and key phrases
- **Accessibility**: large typography, high-contrast mode, multilingual UI

---

## 4) Delivery Plan (Prototype Roadmap)

### Phase 0 — Inception (Week 1)
- Finalize requirements and user journeys
- Define map coverage and critical points (gates, clinics, security)
- Create Figma wireframes for core screens

Deliverables:
- Approved scope document
- UX flow diagrams
- Data model draft

### Phase 1 — Core App Setup (Week 2)
- Initialize React Native (Expo + TypeScript)
- Configure Supabase project, auth, and database schema
- Implement login/onboarding and language selection

Deliverables:
- Running app skeleton on iOS + Android
- Authentication operational
- Database schema deployed

### Phase 2 — Indoor Navigation MVP (Weeks 3–4)
- Build destination search and route rendering
- Add indoor path logic and checkpoint navigation
- Show nearby critical locations (clinic/security/gates)

Deliverables:
- Functional indoor navigation prototype
- Validated sample routes

### Phase 3 — Group Tracking + Emergency (Weeks 5–6)
- Group create/join/invite
- Live position sharing with Realtime updates
- SOS flow + nearest help point route

Deliverables:
- Group dashboard and tracking map
- Emergency module end-to-end

### Phase 4 — Translation + AR Proof of Concept (Weeks 7–8)
- Add real-time translation service integration
- Implement camera overlay directional indicators
- Optional initial AR anchor prototype for one route

Deliverables:
- Translation-enabled communication flow
- AR/overlay proof of concept

### Phase 5 — Stabilization & Demo (Week 9)
- QA, bug fixing, UX polishing
- Security and privacy checks
- Prepare demonstration scenario and documentation

Deliverables:
- Prototype demo build (iOS + Android)
- Test report and limitations list
- Final presentation package

---

## 5) Suggested Team Roles

- Mobile Lead (React Native)
- Backend Engineer (Supabase/Postgres)
- UX/UI Designer (Figma)
- QA Engineer (test scenarios + device matrix)
- Product/Domain Coordinator (Hajj/Umrah operations validation)

---

## 6) Risks and Mitigation

- **Indoor positioning accuracy**
  - Mitigation: begin with mapped waypoints + manual checkpoints before sensor-heavy AR.

- **AR complexity on timeline**
  - Mitigation: keep AR as phase 2 proof of concept, not blocking MVP navigation.

- **Network congestion in crowded areas**
  - Mitigation: local caching, retry queues, lightweight payloads.

- **Privacy concerns for live tracking**
  - Mitigation: opt-in tracking, role-based group access, data expiration.

---

## 7) Clear Recommendation Summary

- **Framework**: React Native (Expo + TypeScript) for one codebase on iOS and Android.
- **Database/Backend**: Supabase (Postgres + Auth + Realtime + Storage + Edge Functions).
- **IDE**: Visual Studio Code with mobile and quality extensions.
- **Build/Release**: EAS Build + GitHub Actions CI/CD.
- **Execution strategy**: deliver MVP (auth + navigation + group + emergency) first, then add advanced AR.
