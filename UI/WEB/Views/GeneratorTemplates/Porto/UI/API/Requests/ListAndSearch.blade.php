<?= "<?php\n" ?>

namespace App\Containers\{{ $crud->containerName() }}\UI\API\Requests{{ $crud->solveGroupClasses() }};

use App\Ship\Parents\Requests\Request;

/**
 * Class {{ str_replace('.php', '', $crud->apiRequestFile('ListAndSearch', $plural = true)) }}.
 * 
 * @author [name] <[<email address>]>
 */
class {{ str_replace('.php', '', $crud->apiRequestFile('ListAndSearch', $plural = true)) }} extends Request
{
	/**
     * Define which Roles and/or Permissions has access to this request..
     *
     * @var  array
     */
    protected $access = [
        'roles' => 'admin',
        'permissions' => '{{ $crud->slugEntityName(true) }}.list_and_search',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [
    ];

    /**
     * Defining the URL parameters (`/stores/999/items`) allows applying
     * validation rules on them and allows accessing them like request data.
     *
     * @var  array
     */
    protected $urlParameters = [
    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
