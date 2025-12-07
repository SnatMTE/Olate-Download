mkdir "\od\svn\branches\od-3.x\trunk\docs\generated\pdf"

cd "P:\od\svn\branches\od-3.x\trunk\docs\xml"
p:\aurigadoc\bin\aurigadoc.bat -pdf -XML "user-manual.xml" -OUT "P:\od\svn\branches\od-3.x\trunk\docs\generated\pdf\od3-doc.pdf"

pause