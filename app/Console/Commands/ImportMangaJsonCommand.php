<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Author;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\ChapterThumbnails;
use App\Models\DomainCrawler;
use App\Models\Manga;
use App\Models\MangaAuthor;
use App\Models\MangaCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportMangaJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manga:import-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run file json manga insert db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Start insert file manga.json');
        $path = storage_path() . "/json/manga_json.json";
        $dataList = json_decode(file_get_contents($path), true);
        if (empty($dataList)) {
            return;
        }
        $domain = DomainCrawler::query()->updateOrCreate([
            'domain_name' => 'www.ninemanga.com',
        ], [
            'display_name' => 'ninemanga.com',
            'is_active' => true,
            'description' => 'www.ninemanga.com',
        ]);

        foreach (array_chunk($dataList,50) as $data) {
            foreach ($data as $item) {
                if (empty($item['chapters']) || empty($item['categories']) || empty($item['manga_name'])) {
                    continue;
                }

                DB::transaction(function () use ($item, $domain) {

                    $manga = Manga::query()->updateOrCreate([
                        'title' => $item['manga_name'] ?? '',
                        'domain_id' => $domain->id,
                    ],[
                        'image' => $item['thumbnail'] ?? '',
                        'is_active' => true,
                        'release_at' => !empty($item['last-update']) ? Helper::parseStringToDate($item['last-update'])->timestamp : '',
                        'type' => 'manga',
                        'title' => $item['manga_name'] ?? '',
                        'slug' => Str::slug( $item['manga_name']),
                        'description' => $item['description'] ?? '',
                        'domain_id' => $domain->id,
                    ]);

                    $author = Author::query()->updateOrCreate([
                        'name' => $item['author'] ?? 'N/a',
                    ]);

                    MangaAuthor::query()->updateOrCreate([
                        'manga_id' => $manga->id,
                        'author_id' => $author->id,
                    ]);

                    foreach ($item['categories'] as $category) {
                        if (empty($category)) {
                            continue;
                        }

                        $categoryNew = Category::query()->updateOrCreate([
                            'title' => $category
                        ], [
                            'domain_id' => $domain->id,
                            'title' => $category,
                            'slug' => Str::slug($category),
                        ]);

                        MangaCategory::query()->updateOrCreate([
                            'category_id' => $categoryNew->id,
                            'manga_id' => $manga->id,
                        ]);
                    }

                    $countChapter = 0;
                    foreach ($item['chapters'] as $chapter) {
                        if (empty($chapter['chapter_name'])) {
                            continue;
                        }

                        $countChapter += 1;
                        $chapterItem = Chapter::query()->updateOrCreate([
                            'manga_id' => $manga->id,
                            'name' => $chapter['chapter_name'],
                        ]);

                        $countThumbnail = 0;
                        foreach ($chapter['page_list'] as $pageThumbnail) {
                            if (empty($pageThumbnail)) {
                                continue;
                            }

                            $countThumbnail += 1;
                            ChapterThumbnails::query()
                                ->create([
                                    'chapter_id' => $chapterItem->id,
                                    'thumbnail_url' => $pageThumbnail,
                                ]);
                        }

                        $chapterItem->update([
                            'thumbnail_count' => isset($chapterItem->thumbnail_count) && $chapterItem->thumbnail_count > 0 ? : $countThumbnail
                        ]);
                    }

                    $manga->update([
                        'chapter_count' => isset($manga->chapter_count) && $manga->chapter_count > 0 ? $manga->chapter_count + $countChapter : $countChapter,
                    ]);
                });
            }
        }
        Log::info('End insert file manga.json');

        return 0;
    }
}
