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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Barryvdh\Debugbar\Facade as Debugbar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
                    ->visible($isEdit) // Check if the current path matches the edit route
                    ->schema([
                        Placeholder::make('Current certificate')
                            ->content(function ($record) {
                                if ($record) {
                                    // Fetch the existing attachment record
                                    $attachment = \App\Models\Attachment::where('attachmentable_type', \App\Models\Course::class)
                                        ->where('attachmentable_id', $record->id)
                                        ->first();

                                    // Return the file's original name if found
                                    return $attachment ? basename($attachment->file_url) : 'No file uploaded yet.';
                                }

                                return 'No file uploaded yet.'; // Default message if no record
                            }),
                        Forms\Components\FileUpload::make('file_attachment') // Correct component for file upload
                            ->directory('uploads/course_uploads_dir')
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->preserveFilenames()  // Ensure the original filename is preserved
                            ->label('Upload/Change Course Certificate')
                            ->saveUploadedFileUsing(function ($file, $state, $set, $record) {
                                try {
                                    // Fetch the existing attachment for the course, if any
                                    $attachment = \App\Models\Attachment::where('attachmentable_type', \App\Models\Course::class)
                                        ->where('attachmentable_id', $record->id)
                                        ->first();

                                    // If an old attachment exists, delete it from the disk and database
                                    if ($attachment) {
                                        // Delete the old file from the disk
                                        $oldFilePath = storage_path('app/public/' . $attachment->file_url);
                                        if (file_exists($oldFilePath)) {
                                            unlink($oldFilePath);  // Delete the old file from the directory
                                        }

                                        // Delete the old attachment record from the database
                                        $attachment->delete();
                                    }

                                    // Store the new file using its original name
                                    $path = $file->storeAs('uploads/course_uploads_dir', $file->getClientOriginalName(), 'public');

                                    // Create or update the attachment record with the new file
                                    \App\Models\Attachment::updateOrCreate(
                                        [
                                            'attachmentable_type' => \App\Models\Course::class,
                                            'attachmentable_id' => $record->id ?? null,
                                        ],
                                        [
                                            'file_url' => $path,
                                            'file_type' => pathinfo($path, PATHINFO_EXTENSION),
                                        ]
                                    );

                                    return $path;  // Return the new file path after saving
                                } catch (\Exception $e) {
                                    Log::error('File upload error: ' . $e->getMessage());
                                    return null;  // Handle errors gracefully
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
