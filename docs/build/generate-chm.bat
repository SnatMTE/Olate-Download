echo Olate Download Documentation Generation
echo Olate Ltd
echo ----------------------------
echo 1/2. Copying XML sources...

mkdir "C:\Aurigadoc\bin\od-docs"
mkdir "C:\Aurigadoc\bin\od-docs\xml"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\xml" /q /y /v /i /e
cd "C:\Aurigadoc\bin"

echo 2/2. Generating CHM documentation...

mkdir "C:\Aurigadoc\bin\od-docs\chm"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\chm" /q /y /v /i /e
del "C:\Aurigadoc\bin\od-docs\chm\user-manual.xml"
aurigadoc.bat -chm -XML "od-docs\xml\user-manual.xml" -OUT "od-docs\chm"

echo ----------------------------
echo Generation Completed 

pause