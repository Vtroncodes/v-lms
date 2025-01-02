<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CourseResource\RelationManagers;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Course Title'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
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
                    ->label('Required Prerequisites (JSON format)')
                    ->placeholder('[1, 2, 3]'), // Example placeholder for JSON input
                Forms\Components\TextInput::make('certificate_url')
                    ->nullable()
                    ->label('Certificate URL'),
                // Forms\Components\TextInput::make('directory_path')
                //     ->required()
                //     ->label('Directory Path'),
                Forms\Components\HasManyRepeater::make('lesson_id')
                ->relationship('lessons') // Use HasManyRepeater for managing lessons
               
                    ->schema([
                        Forms\Components\Select::make('lesson_id')
                            ->relationship('lesson', 'title') // Assuming you have a relationship defined in the Course model
                            ->required()
                            ->label('Select Lesson'),
                    ])
                    ->createItemButtonLabel('Add Lesson'),
                // Other fields...

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
            //
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
