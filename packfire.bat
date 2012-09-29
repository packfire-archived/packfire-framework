@echo off
REM Packfire's executable setup shell script
SET SETUPPATH = %~dp0
php %SETUPPATH%packfire/setup/packfire.php %*