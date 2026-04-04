# Changelog

All notable changes to the ElafSchool management system will be documented in this file.

## [1.1.0] - 2026-04-04

### Added
- **Subject Pool System**: Introduced a many-to-many relationship between Subjects and Grade Levels.
- **Hybrid Mapping Interface**: New UI for mapping subjects to multiple classes with unique elective/compulsory statuses.
- **Subject Metadata**: Added `description` field to the subject pool for curriculum details.
- **Improved Authentication Emails**: Added `school-welcome` and `password-changed` email templates for the school owner registration flow.

### Changed
- **Academic Standardization**: Decoupled Grade Levels, Sections, and Fee Structures from specific campuses. These are now managed at the school-wide level to provide consistency across all campuses.
- **Database Schema**: Updated `fee_structures`, `sections`, and `subjects` tables to support nullable campus dependencies and pivot relationships.
- **Code Hardening**: Replaced deprecated `$request->get()` calls with `$request->input()` across all administrative controllers.
- **Static Analysis Improvement**: Transitioned from `auth()` helper to `Auth` facade in controllers to enhance type-safety and IDE support.

### Fixed
- Resolved multiple Intelephense warnings regarding undefined methods on the `auth()` helper.
- Standardized `school_id` scoping in administrative controllers to prevent cross-tenant data leakage.

## [1.0.0] - 2026-04-03
- Initial Phase 1 release with core management modules.
