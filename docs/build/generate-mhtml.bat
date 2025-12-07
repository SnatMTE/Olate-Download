echo Olate Download Documentation Generation
echo Olate Ltd
echo ----------------------------
echo 1/2. Copying XML sources...

mkdir "C:\Aurigadoc\bin\od-docs"
mkdir "C:\Aurigadoc\bin\od-docs\xml"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\xml" /q /y /v /i /e
cd "C:\Aurigadoc\bin"

echo 2/2. Generating Multi-HTML documentation...

mkdir "C:\Aurigadoc\bin\od-docs\mhtml"
xcopy "C:\Olate\Products\Olate Download\Olate Download\trunk\docs\xml" "C:\Aurigadoc\bin\od-docs\mhtml" /q /y /v /i /e
del "C:\Aurigadoc\bin\od-docs\mhtml\user-manual.xml"
aurigadoc.bat -mhtml -XML "od-docs\xml\user-manual.xml" -OUT "od-docs\mhtml"

echo ----------------------------
echo Generation Completed 

pause