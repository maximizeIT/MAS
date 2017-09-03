# DB & Web Project

Demo: http://maximizeIT.github.io/MAS

## Getting Started

#### 1) Install software and node_modules

**Required software:**

Git: https://git-scm.com/downloads

npm: https://nodejs.org/en/download/

Gulp: https://gulpjs.com

1) Clone (or download zip).

```
git clone git@github.com:maximizeIT/MAS.git
```
```
cd MAS
```

2) Run in root directory:

```
npm install
```

3) Enter DB configuration in:

root/
- assets/scripts/db/DBConfig.php (DB user with read only rights)
- search/db/DBConfig_ro.php (DB user with read only rights)
- admin/assets/db/DBConfig_rw.php (DB user with read & write rights)

#### 2) Run application (via built-in web server)

```
gulp
```

Note: Browser reloads automatically upon saving of a .php file.
