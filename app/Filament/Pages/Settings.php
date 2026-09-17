<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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

    public function mount(): void
    {
        $pengumuman = Setting::getValue('pengumuman');

        $this->form->fill([
            'logo' => Setting::getValue('logo')->value,
            'pengumuman' => $pengumuman->value,
            'pengumuman_aktif' => (bool) $pengumuman->is_active,
            'masa_aktif_user_bulan' => Setting::getMasaAktifUserBulan(),
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
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $pengumumanAktif = $data['pengumuman_aktif'] ?? true;
        unset($data['pengumuman_aktif']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::where('key', 'pengumuman')->update(['is_active' => $pengumumanAktif]);

        Notification::make()
            ->title('Setting berhasil disimpan')
            ->success()
            ->send();
    }
}
