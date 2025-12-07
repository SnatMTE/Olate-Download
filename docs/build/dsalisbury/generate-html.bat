mkdir "P:\od\svn\branches\od-3.x\trunk\docs\generated\html"

xcopy "P:\od\svn\branches\od-3.x\trunk\docs\xml" "P:\od\svn\branches\od-3.x\trunk\docs\generated\html" /q /y /v /i /e
del "P:\od\svn\branches\od-3.x\trunk\docs\generated\html\user-manual.xml"

cd "P:\od\svn\branches\od-3.x\trunk\docs\xml\"
p:\aurigadoc\bin\aurigadoc.bat -html -XML "user-manual.xml" -OUT "\od\svn\branches\od-3.x\trunk\docs\generated\html\od3-documentation.html"

