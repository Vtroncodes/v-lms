<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LessonResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LessonResource\RelationManagers;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Lessons Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Lesson Title'),
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->label('Description'),
                Forms\Components\Select::make('topics')
                    ->label('Topics')
                    ->relationship('topics', 'title')
                    ->multiple() // Allow selecting multiple lessons
                    ->preload()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        // Update a temporary array to reflect selected lessons
                        $set('selected_topics', $state);
                        Debugbar::enable();
                        Debugbar::info($state);
                    }),
                // Forms\Components\Repeater::make('topic_orders')
                //     ->label('Topics with Order')
                //     ->schema([
                //         Forms\Components\TextInput::make('topic_title') // Display topic title
                //             ->label('Topic Title')
                //             ->disabled(), // Make it readonly
                //         Forms\Components\TextInput::make('order') // Input for specifying order
                //             ->label('Order')
                //             ->numeric()
                //             ->required(),
                //     ])
                //     ->columns(2)
                //     ->reactive()
                //     ->hidden(fn(\Filament\Forms\Get $get) => empty($get('selected_topics'))) // Hide if no lessons are selected
                //     ->afterStateHydrated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                //         $selectedTopics = $get('selected_topics');
                //         Debugbar::info($selectedTopics, 'selected_topics');
                //         if (!empty($selectedTopics)) {
                //             $topicDetails = \App\Models\Topic::whereIn('id', $selectedTopics)->get(['id', 'title']);
                //             $orders = $topicDetails->map(fn($topic) => [
                //                 'topic_title' => $topic->title,
                //                 'topic_id' => $topic->id,
                //                 'order' => null, // Placeholder for order
                //             ])->toArray();
                //             $set('topic_orders', $orders);
                //         }
                //     }),
                Forms\Components\Repeater::make('topic_orders')
                    ->label('Topics with Order')
                    ->schema([
                        Forms\Components\TextInput::make('topic_title') // Display topic title
                            ->label('Topic Title')
                            ->disabled(), // Make it readonly
                        Forms\Components\TextInput::make('order') // Input for specifying order
                            ->label('Order')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2)
                    ->reactive()
                    ->hidden(fn(\Filament\Forms\Get $get) => empty($get('selected_topics'))) // Hide if no lessons are selected
                    ->afterStateHydrated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                        $selectedTopics = $get('selected_topics');
                        Debugbar::info($selectedTopics, 'selected_topics');
                        if (!empty($selectedTopics)) {
                            // Get topic details based on selected topics
                            $topicDetails = \App\Models\Topic::whereIn('id', $selectedTopics)->get(['id', 'title']);

                            // Map the topics with an empty 'order' field for later input
                            $orders = $topicDetails->map(fn($topic) => [
                                'topic_title' => $topic->title, // Set the title to the 'topic_title' field
                                'topic_id' => $topic->id, // Store the topic ID for later association
                                'order' => null, // Placeholder for order
                            ])->toArray();

                            // Set the 'topic_orders' field with the selected topics and placeholder orders
                            $set('topic_orders', $orders);
                        }
                    }),

            ]);
    }

    // public static function saving(Lesson $lesson, array $data)
    // {

    //     if (isset($data['topic_orders'])) {
    //         $topicOrders = collect($data['topic_orders'])->mapWithKeys(function ($item) {
    //             return [$item['topic_id'] => ['order' => $item['order']]];
    //         });

    //         // Sync lessons with order to the pivot table
    //         $lesson->topics()->sync($topicOrders);
    //     }
    // }

    public static function saving(Lesson $lesson, array $data)
    {
        if (isset($data['topic_orders'])) {
            $topicOrders = collect($data['topic_orders'])->mapWithKeys(function ($item) {
                return [$item['topic_id'] => ['topic_order' => $item['order']]];
            });

            // Sync topics with the order in the pivot table
            $lesson->topics()->sync($topicOrders);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('topics')
                    ->label('Topics')
                    ->getStateUsing(fn($record) => $record->topics
                        ? $record->topics
                        ->map(fn($topic) => "{$topic->title} (Order: {$topic->pivot->topic_order})")
                        ->join(', ')
                        : 'No topics associated'),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
