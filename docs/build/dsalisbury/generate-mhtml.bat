mkdir "\od\svn\branches\od-3.x\trunk\docs\generated\mhtml"

xcopy "P:\od\svn\branches\od-3.x\trunk\docs\xml" "P:\od\svn\branches\od-3.x\trunk\docs\generated\mhtml" /q /y /v /i /e
del "P:\od\svn\branches\od-3.x\trunk\docs\generated\mhtml\user-manual.xml"

cd "P:\od\svn\branches\od-3.x\trunk\docs\xml"
p:\aurigadoc\bin\aurigadoc.bat -mhtml -XML "user-manual.xml" -OUT "P:\od\svn\branches\od-3.x\trunk\docs\generated\mhtml"

pause