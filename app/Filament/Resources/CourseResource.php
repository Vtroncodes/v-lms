<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use App\Models\Attachment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\MediaLibrary\HasMedia;
use Barryvdh\Debugbar\Facade as Debugbar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;


class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses Management';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->label('Course Title'),
            Forms\Components\Textarea::make('description')
                ->nullable()
                ->label('Description'),
            Forms\Components\TextInput::make('duration')
                ->required()
                ->label('Duration (in hours)'),
            Forms\Components\Select::make('level')
                ->required()
                ->options([
                    'Beginner' => 'Beginner',
                    'Intermediate' => 'Intermediate',
                    'Advanced' => 'Advanced',
                ])
                ->label('Course Level'),
            Forms\Components\Select::make('course_type')
                ->required()
                ->options([
                    'live' => 'Live',
                    'recorded' => 'Recorded',
                    'hybrid' => 'Hybrid',
                ])
                ->label('Course Type'),
            Forms\Components\TextInput::make('allowed_retakes')
                ->nullable()
                ->label('Allowed Retakes'),
            Forms\Components\TextInput::make('required_prerequisites_course_id')
                ->nullable()
                ->label('Required Prerequisites (JSON format)'),
        
            Forms\Components\Grid::make(12) // Define a grid with two columns
                ->schema([
                    // Left column (Form fields)
                    Forms\Components\Grid::make(8) // 8 columns for left side (form fields)
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->label('Course Title'),
                            Forms\Components\Textarea::make('description')
                                ->nullable()
                                ->label('Description'),
                            Forms\Components\TextInput::make('duration')
                                ->required()
                                ->label('Duration (in hours)'),
                            Forms\Components\Select::make('level')
                                ->required()
                                ->options([
                                    'Beginner' => 'Beginner',
                                    'Intermediate' => 'Intermediate',
                                    'Advanced' => 'Advanced',
                                ])
                                ->label('Course Level'),
                            Forms\Components\Select::make('course_type')
                                ->required()
                                ->options([
                                    'live' => 'Live',
                                    'recorded' => 'Recorded',
                                    'hybrid' => 'Hybrid',
                                ])
                                ->label('Course Type'),
                            Forms\Components\TextInput::make('allowed_retakes')
                                ->nullable()
                                ->label('Allowed Retakes'),
                            Forms\Components\TextInput::make('required_prerequisites_course_id')
                                ->nullable()
                                ->label('Required Prerequisites (JSON format)'),
                        ])
                        ->columnSpan(8), // Set this grid to take 8 out of 12 columns
        
                    // Right column (Upload attachments)
                    Forms\Components\Grid::make(4) // 4 columns for the right side (attachments)
                        ->schema([
                            Section::make('Upload attachments')->schema([
                                Forms\Components\Grid::make(12)
                                    ->schema([
                                        FileUpload::make('file_attachment')
                                            ->label('Certificate')
                                            ->directory('uploads/course_uploads_dir')
                                            ->disk('public')
                                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                                            ->preserveFilenames()
                                            ->saveUploadedFileUsing(function ($file, $state, $set, $record) {
                                                // Just store the file and return its path
                                                if (!$file) {
                                                    return null; // No file uploaded
                                                } else {
                                                    $path = $file->store('uploads/course_uploads_dir', 'public');
                                                    // Debugbar::info("path: ", $path);
                                                    collect([$path, $file])->debug();
        
                                                    Attachment::create([
                                                        'attachmentable_type' => Course::class,
                                                        'attachmentable_id' => $record->id,  // ID of the newly created course
                                                        'file_url' => $path,
                                                        'file_type' => pathinfo($path, PATHINFO_EXTENSION),
                                                    ]);
        
                                                    return $path;
                                                }
                                            })
                                            ->columnSpan(12),
                                    ]),
                            ]),
                        ])
                        ->columnSpan(4), // Set this grid to take 4 out of 12 columns
                ]),
            ]);
        
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('duration')->sortable(),
                Tables\Columns\TextColumn::make('level')->sortable(),
                Tables\Columns\TextColumn::make('course_type')->sortable(),
                Tables\Columns\TextColumn::make('lessons')->label('Lessons')->getStateUsing(fn($record) => $record->lessons
                    ->map(fn($lesson) => "{$lesson->title}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created At')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LessonsRelationManager::class,
            RelationManagers\QuizzesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
