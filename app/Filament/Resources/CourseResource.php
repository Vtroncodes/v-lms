<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses Management';

    public static function form(Form $form): Form
    {
        Debugbar::enable();
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
                Forms\Components\TextInput::make('certificate_url')
                    ->nullable()
                    ->label('Certificate URL'),

                // Forms\Components\Select::make('lessons')
                //     ->label('Lessons')
                //     ->relationship('lessons', 'title') // Relationship with 'Lessons'
                //     ->multiple() // Allow selecting multiple lessons
                //     ->preload(),
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
                    ->map(fn($lesson) => "{$lesson->title} (Order: {$lesson->pivot->lesson_order})")
                    ->join(', '))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           RelationManagers\LessonsRelationManager::class,
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
