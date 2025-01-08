<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use App\Models\Attachment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\MediaLibrary\HasMedia;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Barryvdh\Debugbar\Facade as Debugbar;
use Filament\Forms\Components\FileUpload;
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
        //   /  Debugbar::info($operation);
        $isEdit = $form->getOperation() === "edit";
        return $form
            ->schema([
                Section::make('Course Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->label('Course Title'),

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

                    Forms\Components\TextInput::make('duration')
                        ->required()
                        ->label('Duration (in hours)'),

                    Forms\Components\RichEditor::make('description')
                        ->nullable()
                        ->label('Description')
                        ->columnSpan('full'),

                    Forms\Components\TextInput::make('allowed_retakes')
                        ->nullable()
                        ->label('Allowed Retakes'),

                    Forms\Components\TextInput::make('required_prerequisites_course_id')
                        ->nullable()
                        ->label('Required Prerequisites (JSON format)')
                        ->rules(['json']), // Validate JSON if required
                ])->columnSpan(2)->columns(2),

                Section::make('Upload Attachments')
                   // ->visible(fn() => request()->routeIs('filament.resources.courses.edit')) // Ensure it's visible only on edit
                    ->visible($isEdit) // Check if the current path matches the edit route
                    ->schema([
                        Forms\Components\FileUpload::make('file_attachment') // Correct component for file upload
                            ->label('Certificate')
                            ->directory('uploads/course_uploads_dir')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->preserveFilenames()
                            ->saveUploadedFileUsing(function ($file, $state, $set, $record) {
                                try {
                                    $path = $file->store('uploads/course_uploads_dir', 'public');
                
                                    Attachment::create([
                                        'attachmentable_type' => Course::class,
                                        'attachmentable_id' => $record->id ?? null, // Set dynamically
                                        'file_url' => $path,
                                        'file_type' => pathinfo($path, PATHINFO_EXTENSION),
                                    ]);
                
                                    return $path;
                                } catch (\Exception $e) {
                                    Log::error('File upload error: ' . $e->getMessage());
                                    return null;
                                }
                            }),
                    ])->columnSpan(1),
            ])->columns(3);
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
