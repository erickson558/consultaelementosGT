On Error Resume Next
Dim args, excel, book, fso
Set args = WScript.Arguments

If args.Count < 2 Then
  WScript.Echo "Uso: convert_excel.vbs archivo_entrada.xls archivo_salida.csv"
  WScript.Quit 1
End If

inputFile = CreateObject("Scripting.FileSystemObject").GetAbsolutePathName(args(0))
outputFile = args(1)

Set excel = CreateObject("Excel.Application")
excel.DisplayAlerts = False

Set book = excel.Workbooks.Open(inputFile, 0, True)
If Err.Number <> 0 Then
  WScript.Echo "❌ Error al abrir: " & inputFile
  excel.Quit
  WScript.Quit 1
End If

book.SaveAs outputFile, 6
If Err.Number <> 0 Then
  WScript.Echo "❌ Error al guardar como CSV: " & outputFile
  book.Close False
  excel.Quit
  WScript.Quit 1
End If

book.Close False
excel.Quit
WScript.Quit 0
