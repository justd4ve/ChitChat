# Installation
- Import file dump.sql into db
- Backend
  - Download dependencies using `composer install` command in the `/backend` directory
  - Upload backend files to the server
  - edit rewrite in file .htaccess to /backend path
  - change db connection (+token key) variables in file backend/.env
  - allow write access into directory 'logs'
- Frontend
  - Install dependencies using `npm install` command in directory `/frontend`
  - In file `/frontend/src/main.js` set up the base url for backend directory
  - Run by command `npm run dev` in directory `frontend`
    - the app starts on http://localhost:8080
	- alternatively you can build the app using command `npm run build`
		- place dist directory on server
		- build note: img paths are absolute, its necessary to change them after building the app in directory `\dist\static\css` in file with .css extension to relative (`../img/en.png`);
		furthermore it is necessary to change links in the index.php file from `/static` to `./static`