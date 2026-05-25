# Contributing to InfoHub

Thank you for contributing to InfoHub. This guide explains the safest way to work with the repository and avoid leaking secrets.

## 1. Environment files

- Never commit `.env` to Git.
- Use `.env.example` to document required local environment variables.
- If you need to add a new environment variable, update `.env.example` and do not store real credentials in the repo.

## 2. Local setup

1. Copy the example file:

```bash
cp .env.example .env
```

2. Update `.env` with local values.
3. Do not share `.env` with anyone.

## 3. Secret handling

- secrets such as API keys, OAuth client IDs/secrets, or database passwords must stay out of version control.
- If a secret is accidentally committed, rotate/revoke it immediately and remove it from history.
- GitHub push protection may block pushes containing known secret patterns.

## 4. Git hygiene

- Keep commits focused and descriptive.
- Use `git add -p` when needed to stage only the intended changes.
- If you rewrite history, prefer `git push --force-with-lease` over `git push --force`.

## 5. Removing sensitive files from history

If `.env` or another sensitive file was committed, use a history rewrite tool such as BFG or `git filter-repo`.

Example with `git filter-branch` (use only if other tools are unavailable):

```bash
git rm --cached --ignore-unmatch .env
echo ".env" >> .gitignore
git add .gitignore
git commit -m "Remove .env from repository"
git filter-branch --force --index-filter "git rm --cached --ignore-unmatch .env" --prune-empty -- --all
git for-each-ref --format='%(refname)' refs/original/ | xargs -n 1 git update-ref -d
git reflog expire --expire=now --all
git gc --prune=now --aggressive
git push --force-with-lease rukundo main
```

## 6. Branch workflow

- Work on feature branches, not directly on `main`.
- Create branches with meaningful names, e.g. `feature/role-hierarchy`, `fix/auth-session`, or `docs/env-setup`.
- Submit a pull request when your work is ready for review.

## 7. Browser authentication for GitHub

If GitHub prompts you to complete authentication in your browser during `git push`, finish that process before retrying the push.

---

Thank you for helping keep InfoHub secure and maintainable.
