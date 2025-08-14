@echo off
REM CodeForge Database Studio - Test Suite Runner (Windows)
REM This batch file provides an easy way to run the comprehensive test suite
REM for Visual Schema Designer and Intelligent Data Seeding functionality.

setlocal enabledelayedexpansion

echo.
echo 🚀 CodeForge Database Studio Test Suite Runner (Windows)
echo ========================================================
echo.

REM Check if PHP is available
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not available in PATH. Please install PHP or add it to your PATH.
    pause
    exit /b 1
)

REM Get the directory of this batch file
set "SCRIPT_DIR=%~dp0"
set "PROJECT_ROOT=%SCRIPT_DIR%..\..\..\"

REM Check if PHPUnit exists
if not exist "%PROJECT_ROOT%vendor\bin\phpunit.bat" (
    echo ❌ PHPUnit not found. Please run 'composer install' first.
    pause
    exit /b 1
)

REM Parse command line arguments
set "SUITE=all"
set "VERBOSE="
set "COVERAGE="
set "FILTER="
set "SHOW_HELP="

:parse_args
if "%~1"=="" goto :end_parse
if "%~1"=="--help" set "SHOW_HELP=1"
if "%~1"=="-h" set "SHOW_HELP=1"
if "%~1"=="--schema" set "SUITE=schema"
if "%~1"=="--seeding" set "SUITE=seeding"
if "%~1"=="--integration" set "SUITE=integration"
if "%~1"=="--verbose" set "VERBOSE=--verbose"
if "%~1"=="-v" set "VERBOSE=--verbose"
if "%~1"=="--coverage" set "COVERAGE=--coverage-text"
shift
goto :parse_args
:end_parse

if defined SHOW_HELP (
    echo 📖 Usage: run-tests.bat [options]
    echo.
    echo Options:
    echo   --help, -h         Show this help message
    echo   --verbose, -v      Run tests with verbose output
    echo   --coverage         Generate code coverage report
    echo   --schema           Run only Visual Schema Designer tests
    echo   --seeding          Run only Intelligent Data Seeding tests
    echo   --integration      Run only integration tests
    echo.
    echo Examples:
    echo   run-tests.bat                    # Run all tests
    echo   run-tests.bat --schema           # Visual Schema Designer tests only
    echo   run-tests.bat --seeding          # Data Seeding tests only
    echo   run-tests.bat --verbose          # Run with detailed output
    echo.
    pause
    exit /b 0
)

cd /d "%PROJECT_ROOT%"

REM Set test paths based on suite selection
set "TEST_BASE_PATH=packages\codeforge-database-studio\tests\Feature"

if "%SUITE%"=="schema" (
    echo 🎨 Running Visual Schema Designer Tests...
    echo.
    call :run_test_file "%TEST_BASE_PATH%\VisualSchemaDesigner\ComprehensiveVisualSchemaDesignerTest.php" "Visual Schema Designer"
) else if "%SUITE%"=="seeding" (
    echo 🌱 Running Intelligent Data Seeding Tests...
    echo.
    call :run_test_file "%TEST_BASE_PATH%\IntelligentDataSeeding\ComprehensiveIntelligentDataSeedingTest.php" "Intelligent Data Seeding"
) else if "%SUITE%"=="integration" (
    echo 🔗 Running Integration Tests...
    echo.
    call :run_test_file "%TEST_BASE_PATH%\VisualSchemaDesignerAndDataSeedingTestSuite.php" "Integration Tests"
) else (
    echo 🧪 Running Complete Test Suite...
    echo.
    
    echo 📂 Running Visual Schema Designer Tests...
    call :run_test_file "%TEST_BASE_PATH%\VisualSchemaDesigner\ComprehensiveVisualSchemaDesignerTest.php" "Visual Schema Designer"
    
    echo.
    echo 📂 Running Intelligent Data Seeding Tests...
    call :run_test_file "%TEST_BASE_PATH%\IntelligentDataSeeding\ComprehensiveIntelligentDataSeedingTest.php" "Intelligent Data Seeding"
    
    echo.
    echo 📂 Running Integration Tests...
    call :run_test_file "%TEST_BASE_PATH%\VisualSchemaDesignerAndDataSeedingTestSuite.php" "Integration Tests"
)

echo.
echo 📊 Test execution completed!
echo.
echo 💡 Tip: Use '--verbose' for detailed output or '--help' for more options
echo 🌐 Professional Support: contact@hardikkanajariya.in
echo 📚 Documentation: https://codeforge.hardikkanajariya.in/codeforge-database-studio
echo.
pause
exit /b 0

:run_test_file
set "TEST_FILE=%~1"
set "TEST_NAME=%~2"

if not exist "%TEST_FILE%" (
    echo ❌ Test file not found: %TEST_FILE%
    exit /b 1
)

echo 🔄 Executing %TEST_NAME% tests...

REM Build PHPUnit command
set "PHPUNIT_CMD=vendor\bin\phpunit.bat"
if defined VERBOSE set "PHPUNIT_CMD=%PHPUNIT_CMD% %VERBOSE%"
if defined COVERAGE set "PHPUNIT_CMD=%PHPUNIT_CMD% %COVERAGE%"
set "PHPUNIT_CMD=%PHPUNIT_CMD% "%TEST_FILE%""

REM Execute the test
%PHPUNIT_CMD%

if %errorlevel% equ 0 (
    echo ✅ %TEST_NAME%: PASSED
) else (
    echo ❌ %TEST_NAME%: FAILED ^(Exit Code: %errorlevel%^)
)

exit /b %errorlevel%
