## ToDo List Project

## Description

The ToDo List project is a core initiative of a freshly established startup, focusing on a daily task management application. This application, developed quickly to demonstrate viability to potential investors, serves as a Minimum Viable Product (MVP). Built with the PHP Symfony framework, known for its robustness and flexibility, the application aims at providing a straightforward and efficient way to manage daily tasks.

## Purpose

The primary purpose of the ToDo List project is to enhance the application's quality across various dimensions. Quality, in this context, encompasses code quality, user experience, team collaboration, and the overall workability of the project. The project aims to not only meet the foundational needs of daily task management but also to ensure reliability, user satisfaction, and ease of maintenance and enhancement.

## Objectives

The project's objectives are detailed in the project specifications and include:

- **Anomaly Corrections**: Ensuring tasks are associated with the authenticated user upon creation and cannot have their author modified upon editing. Tasks created prior to this implementation should be linked to an "anonymous" user.

- **Role Assignment for Users**: Enabling role selection (ROLE_USER, ROLE_ADMIN) during user creation and modification, enhancing user management capabilities.

- **Authorization Enhancements**: Restricting access to user management pages to admins only and defining clear rules on who can delete tasks, including those assigned to the "anonymous" user.

- **Automated Testing Implementation**: Establishing a suite of unit and functional tests using PHPUnit (and optionally Behat for functional testing) to validate application behavior aligns with requirements. This includes preparing test data and achieving a code coverage rate of over 70%.

- **Technical Documentation**: Producing documentation to guide future junior developers through authentication implementation, understanding file modifications, the authentication process, and user storage.

- **Collaboration Documentation**: Creating a document outlining how developers should proceed with project modifications, detailing the quality process and rules to adhere to.

- **Code Quality and Performance Audit**: Conducting a code audit to assess the technical debt, focusing on code quality and application performance, utilizing tools like Codacy, CodeClimate, Symfony Profiler, Blackfire, or New Relic.

These objectives are designed to address existing issues, introduce critical features, and lay down a robust foundation for future development, ensuring the application's growth and sustainability.

## Installation

1. Clone the GitHub Repository: run

   ```
   git clone https://github.com/Teko13/ToDoAndCo.git
   ```

2. Install Dependencies: run

   ```
   composer install
   ```

3. Environment Variables Configurations

## Production Environment

For the production environment, environment variables should be set in either the `.env` or `.env.local` files. The `.env` file is the default for environment configurations and is committed to your version control system.

To configure your production environment variables:

- Copy the `.env` file to `.env.local` if it does not already exist.
- Edit the `.env.local` file and set the environment variables specific to your production environment.
- Make sure that the `.env.local` file is ignored by your version control system (e.g., by adding it to your `.gitignore` file).

## Testing Environment

For the testing environment, you should use the `.env.test` and `.env.test.local` files. The `.env.test` file is used to set default configurations specific to the test environment, while `.env.test.local` can be used for overriding these defaults with values specific to your local test environment.

Config your testing environment variables follows a similar process:

- Copy the `.env.test` file to `.env.test.local` if it does not already exist.
- Edit the `.env.test.local` file and set the environment variables specific to your testing environment.
- Ensure that the `.env.test.local` file is ignored by your version control system.

4. Create Database: run

   ```
   php bin/console doctrine:database:create
   ```

5. Create Schema: run

   ```
   php bin/console doctrine:schema:update --force
   ```

6. Load Data: run
- for dev environment

   ```
   php bin/console d:f:l --group=group_app
   ```
   
- for test environment
     
     ```
   php bin/console d:f:l --group=group_test
   ```

   ## NOTICE:
  Use the previous commands with the `--env=test` option to create the test database and generate test data.

8. Start server: go to public/ folder and start your local server

## Command (Deprecated)

The command 'assign-anon-user' has been created to assign an anonymous user to each task that has no author. This operation can be executed using the command `php bin/console assign-anon-user`. However, it's important to note that this command should no longer be necessary as the application has evolved to automatically assign an author to every new task created. This enhancement ensures that all tasks have associated authors right from their creation, maintaining consistency and accountability within the system's task management process.
