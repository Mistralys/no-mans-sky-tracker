::
:: Batch files / Unit tests run script
::
:: @template-version 1
::

@echo off

cls

echo ----------------------------------------------------
echo RUNNING MAILEDITOR TESTS
echo ----------------------------------------------------
echo.
echo Run specific testsuites with "--testsuite SuiteName"
echo.




echo.
echo -----------------------------------------------------
echo.

cd ../

call vendor/bin/phpunit %*

cd tests

echo.
echo.
