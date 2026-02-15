@echo off 
chcp 65001 >nul

echo REST API kliens projekt inicializálása...

REM --- Fő mappák ---
mkdir app
mkdir app\Controllers
mkdir app\Http
mkdir app\Router
mkdir app\Models
mkdir app\Views
mkdir app\Views\Layout
mkdir app\Views\Components
mkdir app\Views\Counties

REM --- Konfiguráció ---
mkdir config

REM --- Publikus mappa ---
mkdir public

REM --- Egyéb ---
mkdir logs
mkdir storage

echo Mappastruktúra létrehozva.
echo Most futtasd: composer init