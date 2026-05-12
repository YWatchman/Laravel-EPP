# Contributing

This project follows the **Gitflow** branching model.

## Branch structure

| Branch | Purpose |
|--------|---------|
| `main` | Production-ready code. Only receives merges from `release/*` and `hotfix/*`. |
| `develop` | Integration branch. All features land here first. |
| `feature/*` | New features, branched from and merged back into `develop`. |
| `release/*` | Release preparation, branched from `develop`, merged into both `main` and `develop`. |
| `hotfix/*` | Urgent production fixes, branched from `main`, merged into both `main` and `develop`. |

## Day-to-day workflow

### Starting a feature

```bash
git checkout develop
git pull origin develop
git checkout -b feature/my-feature
```

Work, commit, push, then open a PR targeting `develop`.

### Starting a release

```bash
git checkout develop
git pull origin develop
git checkout -b release/1.2.0
# bump version, update changelog, final fixes
git push origin release/1.2.0
```

Open a PR targeting `main`. After merge, also merge into `develop` and tag the release:

```bash
git checkout main && git pull origin main
git tag -a v1.2.0 -m "Release 1.2.0"
git push origin v1.2.0
```

### Fixing a production bug (hotfix)

```bash
git checkout main
git pull origin main
git checkout -b hotfix/fix-critical-bug
# fix, commit
git push origin hotfix/fix-critical-bug
```

Open two PRs: one targeting `main`, one targeting `develop`.

## Branch naming

| Type | Pattern | Example |
|------|---------|---------|
| Feature | `feature/<short-description>` | `feature/domain-transfer` |
| Bug fix | `fix/<short-description>` | `fix/response-code-cast` |
| Release | `release/<semver>` | `release/1.2.0` |
| Hotfix | `hotfix/<short-description>` | `hotfix/sidn-namespace-url` |

## Rules

- `main` and `develop` are protected — direct pushes are blocked.
- Every change requires a PR with at least **1 approval**.
- Stale approvals are dismissed when new commits are pushed.
- Run `./vendor/bin/pest` and `./vendor/bin/pint` before opening a PR.
