<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Quizzes';
    protected static ?string $navigationGroup = 'Quizzes Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->nullable(),

                Forms\Components\TextInput::make('passing_percentage')
                    ->required()
                    ->numeric()                 
                    ->minValue(1)
                    ->maxValue(100)
                    ->label('Passing Percentage'),

                // Forms\Components\Select::make('quizable_type')
                //     ->options([
                //         'App\\Models\\Course' => 'Course',
                //         'App\\Models\\Lesson' => 'Lesson',
                //         'App\\Models\\Topic'  => 'Topic',
                //     ])
                //     ->relationship('quizzable' , 'title')
                //     ->label('Related Type')
                //     ->required()
                //     ->reactive() // Make sure this is reactive
                //     ->afterStateUpdated(function ($state, callable $set) {
                //         // Clear quizable_id when quizable_type changes
                //         $set('quizable_id', null);
                //     }),

                // Forms\Components\Select::make('quizable_id')
                //     ->label('Related To')
                //     ->relationship('quizzable' , 'id')
                //     ->required()
                //     ->options(function ($get) {
                //         $type = $get('quizable_type'); // Get the selected quizable_type

                //         if ($type) {
                //             return $type::query()->pluck('title', 'id'); // Fetch options dynamically
                //         }

                //         return [];
                //     })
                //     ->reactive() // Ensure this updates when quizable_type changes
                //     ->hidden(fn($get) => !$get('quizable_type')) // Hide if no type is selected
                //     ->placeholder('Select Related Record'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('passing_percentage')->label('Passing Percentage'),
                Tables\Columns\TextColumn::make('quizzable.title')->label('Related To'), // Assuming you have a relationship to get title from quizzable
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created At'),
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
            // Define any relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
