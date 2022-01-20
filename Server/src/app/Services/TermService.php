<?php

namespace App\Services;

use App\Models\Term;
use App\Models\TermHasSimilarTerm;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TermService
 * @package App\Services
 */
class TermService
{
  public function __construct(
    protected Term $term,
    protected TermHasSimilarTerm $termHasSimilarTerm
  ) {}

  public function index(object $request): LengthAwarePaginator|Collection
  {
    $query = $this->term->filters($request);
    return (int) $request->per_page ? $query->paginate($request->per_page) : $query->get();
  }

  public function store(object $request): int
  {
    $term = $this->term->create(
      array_merge($request->validated(), [ 'author_id' => auth('api')->user()->id ])
    );

    if(isset($request->similar_terms)) {
      $similarTerms = [];
      foreach($request->similar_terms as $similarTerm) {
        $similarTerms[] = [
          'term_id' => $term->id,
          'similar_term_id' => $similarTerm,
        ];
      }

      $this->termHasSimilarTerm->insert($similarTerms);
    }

    return $term->id;
  }

  public function show(int $id): Model
  {
    return $this->term->whereId($id)->with('similarTerms')->firstOrFail();
  }

  public function update(object $request, int $id): bool
  {
    $term = $this->term->findOrFail($id);
    $term->update($request->validated());

    $this->termHasSimilarTerm->whereTermId($term->id)->forceDelete();

    if(isset($request->similar_terms)) {
      $similarTerms = [];
      foreach($request->similar_terms as $similarTerm) {
        $similarTerms[] = [
          'term_id' => $term->id,
          'similar_term_id' => $similarTerm,
        ];
      }

      $this->termHasSimilarTerm->insert($similarTerms);
    }

    return true;
  }

  public function destroy(int $id): bool
  {
    $this->term->whereId($id)->delete();
    $this->termHasSimilarTerm->whereTermId($id)->delete();

    return true;
  }
}
