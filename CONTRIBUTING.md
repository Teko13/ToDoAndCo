# GitHub Collaboration Guide for ToDo & Co Project

Welcome to the collaboration guide for the ToDo & Co project. This document is intended for all developers wishing to contribute to the project. We will detail the process for making modifications via GitHub, using the Fork system, as well as the quality rules and processes to follow.

## Contribution Process

### 1. Forking the Repository

- Start by creating a "fork" of the main repository. A "fork" is a copy of the repository that you can modify without affecting the original repository.
- On the GitHub page of the ToDo & Co project, click on the "Fork" button located at the top right.

### 2. Cloning Your Fork

- Once you've forked, clone your fork to your local machine to start working on the changes.
- Use the `git clone` command followed by the URL of your fork which you can find on GitHub.

  ```
  git clone <URL of your fork>
  ```

### 3. Creating a Branch

- Create a new branch on which to work. Name it to reflect the feature or fix you are contributing.

  ```
  git checkout -b name_of_your_branch
  ```

### 4. Making Changes

- Perform your modifications, additions, or fixes in the code.
- Test your code locally to ensure it works correctly.

### 5. Commit and Push

- Once your changes are complete, commit them and push your branch to your fork on GitHub.

  ```
  git add .
  git commit -m "Concise description of the changes"
  git push origin your_branch
  ```

### 6. Pull Request (PR)

- On GitHub, navigate to the original ToDo & Co project repository. You should see a button to create a Pull Request from your branch.
- Create the Pull Request by providing a clear description of the changes made and their purpose.

## Quality Process

- **Code Review**: Each Pull Request must be reviewed by at least one other developer before merging. Use this process to discuss modifications and make improvements.
- **Tests**: Ensure that your code comes with relevant unit or functional tests. Tests must pass successfully before any merge.
- **Adherence to Standards**: Follow the coding conventions of the project and best development practices. Use tools like PHP CS Fixer to adhere to Symfony/PSR coding standards.
- **Controller method names**: Avoid the 'Action' suffix in front of controller method names.
- **Code readability**: The code should not contain unnecessary line breaks and must be well indented.
- **Comments**: Explanatory comments are required if necessary.

## Rules to Follow

- **Branch Naming**: Use descriptive names for your branches, e.g., `feature/new-feature` or `fix/bug-fix`.
- **Commit Messages**: Write clear and precise commit messages to explain the changes made.
- **No Direct Pushes**: Never directly push to the main branch. Always use the Pull Request process.
- **Documentation**: Update the documentation if necessary, especially when adding new features.
