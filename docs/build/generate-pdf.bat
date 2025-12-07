echo Olate Download Documentation Generation
echo Olate Ltd
echo ----------------------------
echo 1/2. Copying XML sources...

mkdir "C:\Aurigadoc\bin\od-docs"
mkdir "C:\Aurigadoc\bin\od-docs\xml"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\xml" /q /y /v /i /e
cd "C:\Aurigadoc\bin"

echo 2/2. Generating PDF documentation...

mkdir "C:\Aurigadoc\bin\od-docs\pdf"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\pdf" /q /y /v /i /e
del "C:\Aurigadoc\bin\od-docs\pdf\user-manual.xml"
aurigadoc.bat -pdf -XML "od-docs\xml\user-manual.xml" -OUT "od-docs\pdf\od3-user-manual.pdf"

echo ----------------------------
echo Generation Completed 

pause