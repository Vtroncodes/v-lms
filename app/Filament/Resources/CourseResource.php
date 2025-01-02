<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                // Forms\Components\TextInput::make('certificate_url')
                //     ->nullable()
                //     ->label('Certificate URL'),
                // Forms\Components\TextInput::make('directory_path')
                //     ->required()
                //     ->label('Directory Path'),
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
