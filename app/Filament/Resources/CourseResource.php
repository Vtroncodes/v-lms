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

                Forms\Components\Select::make('lessons')
                    ->label('Lessons')
                    ->relationship('lessons', 'title') // Relationship with 'Lessons'
                    ->multiple() // Allow selecting multiple lessons
                    ->preload()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        // Update a temporary array to reflect selected lessons
                        Debugbar::enable();
                        Debugbar::info($state); // Print selected lessons' IDs
                        $set('selected_lessons', $state);
                    }),

                // Forms\Components\Repeater::make('lesson_orders')
                //     ->label('Order of Lessons')
                //     ->schema([
                //         Forms\Components\TextInput::make('lesson_title') // Display lesson title
                //             ->label('Lesson Title')
                //             ->disabled(), // Make it readonly
                //         Forms\Components\TextInput::make('order') // Input for specifying order
                //             ->label('Order')
                //             ->numeric()
                //             ->required(),
                //     ])
                //     ->columns(2) // Display inputs in two columns
                //     ->reactive() // React to changes in the state
                //     ->hidden(fn(\Filament\Forms\Get $get) => empty($get('selected_lessons'))) // Hide if no lessons are selected
                //     ->afterStateHydrated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                //         $selectedLessons = $get('selected_lessons');

                //         Debugbar::info($selectedLessons, 'Selected Lessons'); // Log selected lesson IDs

                //         if (!empty($selectedLessons)) {
                //             // Fetch lessons based on selected IDs
                //             $lessonDetails = Lesson::whereIn('id', $selectedLessons)->get(['id', 'title']);

                //             Debugbar::info($lessonDetails, 'Fetched Lesson Details'); // Log fetched lesson details

                //             $orders = $lessonDetails->map(fn($lesson) => [
                //                 'lesson_title' => $lesson->title,
                //                 'lesson_id' => $lesson->id,
                //                 'order' => null,
                //             ])->toArray();

                //             // Set the 'lesson_orders' field with the selected lessons and placeholder orders
                //             $set('lesson_orders', $orders);

                //             Debugbar::info($orders, 'Lesson Orders'); // Log orders
                //         }
                //     }),
                Forms\Components\Repeater::make('lesson_orders')
                    ->label('Order of Lessons')
                    ->schema([
                        Forms\Components\Select::make('lesson_id')
                            ->label('Lesson')
                            ->options(
                                fn(\Filament\Forms\Get $get, $record) =>
                                $record
                                    ? $record->lessons()->select('lessons.id', 'lessons.title')->pluck('title', 'id')
                                    : Lesson::pluck('title', 'id')
                            )
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state, $record = null) {
                                $lesson = Lesson::find($state);
                                $existingOrder = $record
                                    ? $record->lessons()->where('lesson_id', $state)->first()?->pivot->lesson_order
                                    : null;

                                $set('lesson_title', $lesson?->title);
                                $set('order', $existingOrder);
                            }),
                        Forms\Components\TextInput::make('lesson_title')
                            ->label('Lesson Title')
                            ->disabled(),
                        Forms\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(3)

            ]);
    }

    public static function saving(Course $course, array $data)
{
    if (isset($data['lesson_orders'])) {

        Debugbar::addMessage($data, 'lesson_orders');
        $lessonOrders = collect($data['lesson_orders'])
            ->mapWithKeys(function ($item) {
                return [
                    $item['lesson_id'] => ['lesson_order' => $item['order']]
                ];
            })
            ->filter(function ($value, $key) {
                return !is_null($key) && !is_null($value['lesson_order']);
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
