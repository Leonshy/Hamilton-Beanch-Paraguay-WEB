<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#387900;padding:20px 24px;">
                            <span style="color:#ffffff;font-size:16px;font-weight:bold;">Hamilton Beach Paraguay</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <h1 style="font-size:18px;color:#111827;margin:0 0 16px;">Nuevo mensaje de contacto</h1>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#374151;">
                                <tr>
                                    <td style="padding:6px 0;width:120px;color:#6b7280;">Nombre</td>
                                    <td style="padding:6px 0;">{{ $contact->full_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Email</td>
                                    <td style="padding:6px 0;"><a href="mailto:{{ $contact->email }}" style="color:#387900;">{{ $contact->email }}</a></td>
                                </tr>
                                @if($contact->phone)
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Teléfono</td>
                                    <td style="padding:6px 0;">{{ $contact->phone }}</td>
                                </tr>
                                @endif
                                @if($contact->subject)
                                <tr>
                                    <td style="padding:6px 0;color:#6b7280;">Asunto</td>
                                    <td style="padding:6px 0;">{{ $contact->subject }}</td>
                                </tr>
                                @endif
                            </table>

                            <div style="margin-top:16px;padding:16px;background:#f9fafb;border-radius:6px;font-size:14px;color:#111827;white-space:pre-wrap;">{{ $contact->message }}</div>

                            <p style="margin-top:24px;">
                                <a href="{{ route('admin.contacts.show', $contact) }}" style="background:#387900;color:#ffffff;text-decoration:none;padding:10px 20px;border-radius:6px;font-size:14px;display:inline-block;">Ver en el panel</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
