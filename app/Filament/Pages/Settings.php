<?php

namespace App\Filament\Pages;

use App\Jobs\SendTelegramReminderJob;
use App\Models\Setting;
use App\Services\TelegramBotClient;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Konfigurasi';

    protected static ?string $navigationLabel = 'Setting';

    protected static ?string $title = 'Setting';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    private const CONTOH_TEMPLATE_BELAJAR = "📚 <b>Pengingat Jadwal Belajar</b>\n\nHalo {nama}, hari ini ({tanggal}) ada materi belajar untuk Anda:\n\n📖 {judul}\n{materi}\nPertemuan ke-{pertemuan}\n\nBuka BISI Online untuk mulai belajar: {link}";

    private const CONTOH_TEMPLATE_UJIAN_PEKANAN = "📝 <b>Pengingat {jenis_ujian}</b>\n\nHalo {nama}, hari ini ({tanggal}) ada {jenis_ujian} untuk Anda:\n\n📖 {materi}\nPekan ke-{pekan}\n\nBuka BISI Online untuk mengerjakan ujian: {link}";

    private const CONTOH_TEMPLATE_UJIAN_AKHIR = "🎓 <b>Pengingat {jenis_ujian}</b>\n\nHalo {nama}, hari ini ({tanggal}) ada {jenis_ujian} untuk Anda:\n\n📖 {materi}\n\nBuka BISI Online untuk mengerjakan ujian: {link}";

    public function mount(): void
    {
        $pengumuman = Setting::getValue('pengumuman');

        $this->form->fill([
            'logo' => Setting::getValue('logo')->value,
            'pengumuman' => $pengumuman->value,
            'pengumuman_aktif' => (bool) $pengumuman->is_active,
            'masa_aktif_user_bulan' => Setting::getMasaAktifUserBulan(),
            'telegram_reminder_jam' => Setting::getTelegramReminderJam(),
            'telegram_reminder_aktif' => Setting::isTelegramReminderActive(),
            SendTelegramReminderJob::TEMPLATE_BELAJAR => Setting::getTelegramReminderTemplate(SendTelegramReminderJob::TEMPLATE_BELAJAR),
            SendTelegramReminderJob::TEMPLATE_UJIAN_PEKANAN => Setting::getTelegramReminderTemplate(SendTelegramReminderJob::TEMPLATE_UJIAN_PEKANAN),
            SendTelegramReminderJob::TEMPLATE_UJIAN_AKHIR => Setting::getTelegramReminderTemplate(SendTelegramReminderJob::TEMPLATE_UJIAN_AKHIR),
            'premium_feature_aktif' => Setting::isPremiumFeatureActive(),
            'daftar_aktif' => Setting::getValue('daftar_aktif')->is_active,
            'daftar_tanggal_mulai' => Setting::getDaftarTanggalMulai(),
            'daftar_tanggal_selesai' => Setting::getDaftarTanggalSelesai(),
            'google_analytics_id' => Setting::getGoogleAnalyticsId(),
            'facebook_pixel_id' => Setting::getFacebookPixelId(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('settings'),
                RichEditor::make('pengumuman')
                    ->label('Pengumuman')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Toggle::make('pengumuman_aktif')
                    ->label('Pengumuman Aktif')
                    ->default(true)
                    ->columnSpanFull(),
                TextInput::make('masa_aktif_user_bulan')
                    ->label('Masa Aktif User (bulan)')
                    ->helperText('User dianggap aktif jika pernah mengikuti ujian dalam sekian bulan terakhir. Dipakai pada filter "User Aktif" di halaman Peserta.')
                    ->numeric()
                    ->minValue(1)
                    ->default(6)
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('telegram_reminder_aktif')
                    ->label('Pengingat Telegram Aktif')
                    ->helperText('Kirim pengingat Telegram ke user saat ada jadwal belajar hari itu.')
                    ->default(true),
                TimePicker::make('telegram_reminder_jam')
                    ->label('Jam Kirim Pengingat')
                    ->seconds(false)
                    ->default('07:00')
                    ->required(),
                Actions::make([
                    FormAction::make('test_telegram')
                        ->label('Kirim Test Notifikasi ke Saya')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->action('testTelegramNotification'),
                ])
                    ->columnSpanFull(),
                Section::make('Template Pesan Pengingat Telegram')
                    ->description('Kosongkan template untuk memakai pesan default bawaan sistem.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        $this->templateField(
                            SendTelegramReminderJob::TEMPLATE_BELAJAR,
                            'Pesan Pengingat Jadwal Belajar',
                            self::CONTOH_TEMPLATE_BELAJAR,
                            SendTelegramReminderJob::PLACEHOLDERS_BELAJAR,
                        ),
                        $this->templateField(
                            SendTelegramReminderJob::TEMPLATE_UJIAN_PEKANAN,
                            'Pesan Pengingat Ujian Pekanan',
                            self::CONTOH_TEMPLATE_UJIAN_PEKANAN,
                            SendTelegramReminderJob::PLACEHOLDERS_UJIAN,
                        ),
                        $this->templateField(
                            SendTelegramReminderJob::TEMPLATE_UJIAN_AKHIR,
                            'Pesan Pengingat Ujian Akhir',
                            self::CONTOH_TEMPLATE_UJIAN_AKHIR,
                            SendTelegramReminderJob::PLACEHOLDERS_UJIAN,
                        ),
                    ])
                    ->columnSpanFull(),
                Toggle::make('premium_feature_aktif')
                    ->label('Fitur Premium Aktif')
                    ->helperText('Tampilkan/sembunyikan tombol "Member Premium", "Materi Video", dan "Ebook Materi" di navigasi. Matikan selama fitur ini belum selesai dikembangkan.')
                    ->default(false)
                    ->columnSpanFull(),
                Toggle::make('daftar_aktif')
                    ->label('Halaman Daftar Aktif')
                    ->helperText(new HtmlString(
                        'Matikan untuk menutup pendaftaran sepenuhnya, tanpa melihat periode di bawah. '
                        . 'Pastikan sudah ada <strong>gelombang</strong> yang dibuat untuk periode pendaftaran ini, '
                        . 'cek/kelola di halaman <a href="' . route('filament.admin.resources.gelombangs.index') . '" target="_blank" class="underline text-primary-600">Gelombang</a>.'
                    ))
                    ->default(true)
                    ->columnSpanFull(),
                TextInput::make('daftar_tanggal_mulai')
                    ->label('Tanggal Mulai Buka (setiap bulan)')
                    ->helperText('Default tanggal 25.')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31)
                    ->default(25)
                    ->required(),
                TextInput::make('daftar_tanggal_selesai')
                    ->label('Tanggal Tutup (setiap bulan)')
                    ->helperText('Default tanggal 31 (otomatis menyesuaikan akhir bulan kalau bulan tersebut lebih pendek, mis. Februari).')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31)
                    ->default(31)
                    ->required(),
                TextInput::make('google_analytics_id')
                    ->label('Google Analytics ID')
                    ->placeholder('G-XXXXXXXXXX')
                    ->helperText('Dipasang otomatis di semua halaman public. Kosongkan untuk mematikan tracking.'),
                TextInput::make('facebook_pixel_id')
                    ->label('Facebook Pixel ID')
                    ->placeholder('123456789012345')
                    ->helperText('Dipasang otomatis di semua halaman public. Kosongkan untuk mematikan tracking.'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    private function templateField(string $key, string $label, string $contoh, array $placeholders): Textarea
    {
        $daftarPlaceholder = collect($placeholders)
            ->map(fn ($keterangan, $nama) => '<code>{' . $nama . '}</code> ' . e($keterangan))
            ->implode('<br>');

        return Textarea::make($key)
            ->label($label)
            ->rows(9)
            ->placeholder($contoh)
            ->helperText(new HtmlString(
                'Placeholder yang tersedia:<br>' . $daftarPlaceholder
                . '<br><br>Format teks: <code>&lt;b&gt;tebal&lt;/b&gt;</code>, <code>&lt;i&gt;miring&lt;/i&gt;</code>, <code>&lt;u&gt;garis bawah&lt;/u&gt;</code>. '
                . 'Baris yang semua placeholder-nya kosong otomatis dihapus. '
                . 'Jika template ditolak Telegram (mis. tag tidak ditutup), pesan default yang dikirim.'
            ))
            ->hintAction(
                FormAction::make('isi_contoh_' . $key)
                    ->label('Isi dengan pesan default')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(fn (Set $set) => $set($key, $contoh))
            )
            ->columnSpanFull();
    }

    public function testTelegramNotification(): void
    {
        if (! config('services.telegram.bot_token')) {
            Notification::make()
                ->title('Bot token Telegram belum diatur')
                ->body('Isi TELEGRAM_BOT_TOKEN di file .env lebih dulu.')
                ->danger()
                ->send();

            return;
        }

        $user = auth()->user();

        if (! $user->telegram_chat_id) {
            $link = $user->getTelegramConnectLink();

            Notification::make()
                ->title('Akun Anda belum terhubung ke Telegram')
                ->body($link
                    ? new HtmlString('Hubungkan dulu lewat link ini: <a href="' . $link . '" target="_blank" class="underline">Hubungkan Telegram</a>, lalu coba test lagi.')
                    : 'Hubungkan dulu lewat tombol "Hubungkan" di halaman depan (homepage), baru coba test lagi.')
                ->warning()
                ->persistent()
                ->send();

            return;
        }

        $sent = app(TelegramBotClient::class)->sendMessage(
            $user->telegram_chat_id,
            "🔔 <b>Test Notifikasi</b>\n\nIni pesan uji coba dari BISI Online. Kalau Anda menerima pesan ini, pengaturan notifikasi Telegram sudah bekerja dengan baik ✅"
        );

        if ($sent) {
            Notification::make()
                ->title('Test notifikasi berhasil dikirim')
                ->body('Cek Telegram Anda.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Gagal mengirim test notifikasi')
                ->body('Cek kembali TELEGRAM_BOT_TOKEN di .env, atau pastikan akun Telegram Anda masih terhubung dengan benar.')
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $pengumumanAktif = $data['pengumuman_aktif'] ?? true;
        unset($data['pengumuman_aktif']);

        $telegramReminderAktif = $data['telegram_reminder_aktif'] ?? true;
        unset($data['telegram_reminder_aktif']);
        $premiumFeatureAktif = $data['premium_feature_aktif'] ?? false;
        unset($data['premium_feature_aktif']);

        $daftarAktif = $data['daftar_aktif'] ?? true;
        unset($data['daftar_aktif']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::where('key', 'pengumuman')->update(['is_active' => $pengumumanAktif]);
        Setting::where('key', 'telegram_reminder_jam')->update(['is_active' => $telegramReminderAktif]);
        Setting::updateOrCreate(['key' => 'premium_feature'], ['is_active' => $premiumFeatureAktif]);
        Setting::updateOrCreate(['key' => 'daftar_aktif'], ['is_active' => $daftarAktif]);

        Notification::make()
            ->title('Setting berhasil disimpan')
            ->success()
            ->send();
    }
}
