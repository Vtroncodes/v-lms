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
use Closure;
use Barryvdh\Debugbar\Facade as Debugbar;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $navigationGroup = 'Courses';

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

                Forms\Components\Select::make('lessons')
                    ->label('Lessons')
                    ->relationship('lessons', 'title') // Use the relationship and the title column from Lesson
                    ->multiple() // Allow selecting multiple lessons
                    ->preload()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        // Update a temporary array to reflect selected lessons
                        $set('selected_lessons', $state);
                        Debugbar::enable();
                        Debugbar::info($state);
                    }),
                
                Forms\Components\Repeater::make('lesson_orders')
                    ->label('Lessons with Order')
                    ->schema([
                        Forms\Components\TextInput::make('lesson_title') // Display lesson title
                            ->label('Lesson Title')
                            ->disabled(), // Make it readonly
                        Forms\Components\TextInput::make('order') // Input for specifying order
                            ->label('Order')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2) // Display inputs in two columns
                    ->reactive() // React to changes in the state
                    ->hidden(fn (\Filament\Forms\Get $get) => empty($get('selected_lessons'))) // Hide if no lessons are selected
                    ->afterStateHydrated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                        $selectedLessons = $get('selected_lessons');
                        if (!empty($selectedLessons)) {
                            $lessonDetails = Lesson::whereIn('id', $selectedLessons)->get(['id', 'title']);
                            $orders = $lessonDetails->map(fn ($lesson) => [
                                'lesson_title' => $lesson->title,
                                'lesson_id' => $lesson->id,
                                'order' => null, // Placeholder for order
                            ])->toArray();
                            $set('lesson_orders', $orders);
                        }
                    }),


            ]);
    }
    public static function saving(Course $course, array $data)
    {
        if (isset($data['lesson_orders'])) {
            $lessonOrders = collect($data['lesson_orders'])->mapWithKeys(function ($item) {
                return [$item['lesson_id'] => ['order' => $item['order']]];
            });

            // Sync lessons with order to the pivot table
            $course->lessons()->sync($lessonOrders);
        }
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
                    ->map(fn($lesson) => "{$lesson->title} (Order: {$lesson->pivot->order})")
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
