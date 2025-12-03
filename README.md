# Demulla Corporate Intranet

This Intranet is a secure, internal communication and information platform built for Demulla. It centralizes announcements, documents, events, organizational structure, and departmental resources into one accessible system for all employees. The platform is designed using Laravel and MySQL, with a strong emphasis on reliability, security, and maintainability within a fintech environment.

---

## Overview

This intranet provides a controlled environment where employees can access up-to-date company information, communicate across departments, and manage internal resources. Access is strictly limited to employees, and the system enforces secure authentication, role-based permissions, and full audit visibility.

The system is optimized for deployment on cPanel using Laravel for backend logic, MySQL for persistent storage, and a modular structure for future enhancements.

---

## Core Features

### Internal Communication

* Company-wide announcements
* Department memos
* Newsletters
* Alerts and notifications
* Recognition and awards
* Discussion and feedback tools

### Directory and Organizational Structure

* Interactive org structure and reporting hierarchy
* Department and branch listings
* Searchable employee directory
* Employee profile pages

### Document and Policy Management

* Policy repository
* Job descriptions
* Document library with access control
* Policy acknowledgment tracking
* Version control for updated documents

### Events and Calendar

* Company and departmental events
* Central calendar view
* Event details and RSVP functionality

### Access Control and Governance

* Role-based access control
* Resource-level permissions and scopes
* Department-level approval workflows
* Audit logging for sensitive actions

### Security and Compliance

* Mandatory MFA
* Secure session handling
* Least-privilege permission model
* Compliance-friendly audit trails
* Encrypted communication over HTTPS

---

## Technology Stack

* Backend: Laravel (PHP Framework)
* Frontend: Blade templates or optional SPA
* Database: MySQL
* Hosting: cPanel (Apache, PHP, MySQL)
* Authentication: Laravel authentication with MFA
* Storage: Local or S3-compatible file storage

---

## Content Approval Workflows

The intranet supports structured approval flows depending on the type of content:

* HR approves HR policies and HR-related announcements.
* Department Managers approve departmental announcements.
* Communications Team approves company-wide memos, newsletters, and official updates.
* ICT or Executive Office approves system-wide notices.

Rejected items return to the initiator for revision.

---

## System Architecture (Summary)

* Frontend interacts with the Laravel backend over secure HTTPS.
* Backend handles authentication, authorization, logic, and audits.
* MySQL stores all persistent data including users, content, and logs.
* File storage handles documents, media, and policy files.
* Audit subsystem logs critical actions for compliance.

(Architecture diagram provided separately.)

---

## Monitoring and Maintenance

* System logs reviewed daily through Laravel Log Viewer or server logs.
* Uptime monitoring for backend and database services.
* Error notifications delivered to ICT administrators.
* Storage, CPU, memory, and database performance monitored by the IT team.

---

## Backup and Recovery

* Daily database backups with multi-day retention.
* Daily file backups for document storage.
* Weekly full system backup.
* Backups stored off-server based on retention policies.
* IT Officer performs routine backup checks.
* System Administrator handles restoration when required.

---

## Data Retention

* Announcements: retained for 12 months
* Memos and newsletters: retained for 24 months
* Documents and policies: retained indefinitely or until replaced
* System logs: retained for 90 days
* Former employee accounts: purged after 180 days

---

## Incident Handling

1. Incident is detected or reported.
2. IT Officer assesses severity and attempts first-level resolution.
3. Critical issues are escalated to System Administrator, then Head of ICT when required.
4. All incidents are documented for future reference.
5. Root cause analysis is conducted and preventive measures implemented.

---

## Non-Functional Requirements

* Reliability: Target 99% uptime
* Performance: Pages load within 2 to 3 seconds under normal conditions
* Security: Mandatory MFA, RBAC, encrypted traffic, audit logging
* Scalability: Supports hundreds of employees across departments and branches
* Maintainability: Modular Laravel architecture with clear documentation
* Usability: Accessible on standard browsers and mobile devices

---

## Future Enhancements

* Mobile app or progressive web app
* Advanced search and content classification
* Analytics and reporting dashboards
* Automated workflows for approvals
* HRIS or SCIM-based user provisioning
* Integrated messaging and chat system
