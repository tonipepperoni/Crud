<?php
/* @var $crud App\Containers\Crud\Providers\TestsGenerator */
/* @var $fields [] */
/* @var $test [] */
/* @var $request Request */
?>
<?='<?php'?>


<?= $crud->getClassCopyRightDocBlock() ?>


namespace <?= $crud->studlyCasePlural() ?>;

use <?= $modelNamespace = config('modules.crud.config.parent-app-namespace')."\Models\\".$crud->modelClassName() ?>;
use FunctionalTester;
use Page\Functional\<?= $crud->studlyCasePlural() ?>\<?= $test ?> as Page;
use Page\Functional\<?= $crud->studlyCasePlural() ?>\Destroy as DestroyPage;

class <?= $test ?>Cest
{
    /**
     * Las acciones a realizar antes de cada test.
     *
     * @param  FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        new Page($I);
        $I->amLoggedAs(Page::$adminUser);
    }

    /**
     * Crear 10, luego <?= strtolower($crud->getDestroyBtnTxt()) ?> 2 registros de prueba en la base de
     * datos.
     *
     * @return Illuminate\Database\Eloquent\Collection
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    private function createAndSoftDeleteSomeRecords()
    {
        // creo registros de prueba
        factory(<?= $crud->modelClassName() ?>::class, 10)->create();

        return <?= $crud->modelClassName() ?>::all(['id'])->take(2)
            ->each(function ($item, $key) {
                $item->delete();
            });
    }

    /**
     * Prueba los datos mostrados en el Index del módulo.
     *
     * @param  FunctionalTester $I
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    public function index(FunctionalTester $I)
    {
        $I->wantTo('probar vista index de módulo '.Page::$moduleName);
        
        // creo el registro de prueba
        Page::have<?= $crud->modelClassName() ?>($I);

        $I->amOnPage(Page::$moduleURL);
        $I->see(Page::$moduleName, Page::$titleElem);

        $indexData = Page::getIndexTableData();

        // veo los respectivos datos en la tabla
        foreach (Page::$tableColumns as $column) {
            $I->see($indexData[$column], Page::$table." tbody tr.item-{$indexData['id']} td.$column");
        }
    }

<?php if ($crud->hasDeletedAtColumn($fields)) { ?>
    /**
     * Prueba que sean mostrados los registros en papelera en la tabla del Index
     * según le convenga al usuario, sólo los registros en papelea o registros
     * "normales" junto con los registros en papelera.
     *
     * @param  FunctionalTester $I
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    public function seeTrashedData(FunctionalTester $I)
    {
        $I->wantTo('ver registros en papelera en index, módulo '.Page::$moduleName);
        
        // creo registros de prueba y elimino algunos
        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>Trashed = $this->createAndSoftDeleteSomeRecords();

        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?> = <?= $crud->modelClassName() ?>::all();

        // con registros en papelera
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'withTrashed']]
            )
        );

        // las filas de los registros que están en papelera deben aparecer con
        // la clase danger, es decir con un fondo rojo, las filas que no están
        // eliminadas no tienen la clase danger
        foreach (<?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>Trashed as $item) {
            $I->see($item->id, 'tbody tr.danger td.id');
        }
        foreach (<?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?> as $item) {
            $I->see($item->id, 'tbody tr td.id');
        }

        // sólo registros en papelera
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'onlyTrashed']]
            )
        );

        foreach (<?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>Trashed as $item) {
            $I->see($item->id, 'tbody tr.danger td.id');
        }

        foreach (<?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?> as $item) {
            $I->dontSee($item->id, 'tbody tr td.id');
        }
    }

    /**
     * Prueba que el botón para restablecer los registros en papelera sean
     * mostrados si es que el usuario consulta tales registros.
     *
     * @param  FunctionalTester $I
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    public function seeRestoreButtonIfShownTrashedRecords(FunctionalTester $I)
    {
        $I->wantTo('ver botón restablecer según filtros en Index, módulo '.Page::$moduleName);

        // creo registros de prueba y elimino algunos
        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>Trashed = $this->createAndSoftDeleteSomeRecords();

        // si el usuario no desea mostrar los registros en papelera, el botón no
        // debe ser mostrado
        $I->amOnPage(Page::$moduleURL);
        $I->dontSee(Page::$restoreManyBtn, Page::$restoreManyBtnElem);

        // si ha decidido mostrar los registros en papelera, el botón debe ser
        // mostrado
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'withTrashed']]
            )
        );
        $I->see(Page::$restoreManyBtn, Page::$restoreManyBtnElem);
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'onlyTrashed']]
            )
        );
        $I->see(Page::$restoreManyBtn, Page::$restoreManyBtnElem);
        // las filas borradas de la tabla también deben mostrar el botón
        $I->see(Page::$restoreBtn, Page::$restoreBtnElem);
    }

    /**
     * Prueba que el botón de mover registros a "papelera" se muestre sólo
     * cuando haya algo que mover, por ejemplo para el caso en que son
     * mostrados sólo los registros de la papelera, en ese caso es
     * innecesario mostrar dicho botón.
     *
     * @param  FunctionalTester $I
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    public function dontSeeTrashButtonIfShownOnlyTrashedData(FunctionalTester $I)
    {
        $I->wantTo('ocultar botón <?= $crud->getDestroyBtnTxt() ?> según filtros en Index, módulo '.Page::$moduleName);

        // creo registros de prueba y elimino algunos
        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>Trashed = $this->createAndSoftDeleteSomeRecords();

        // sólo se oculta el botón si lo unico que se desea consultar son los
        // registros en papelera
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'onlyTrashed']]
            )
        );
        $I->dontSee(DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtn, DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtnElem);
        $I->amOnPage(Page::$moduleURL);
        $I->see(DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtn, DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtnElem);
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'withTrashed']]
            )
        );
        $I->see(DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtn, DestroyPage::$<?= $crud->getDestroyVariableName() ?>ManyBtnElem);
    }

    /**
     * Prueba la funcionalidad de restaurar varios registros movidos a papelera
     * a la vez desde el index.
     *
     * @param  FunctionalTester $I
<?php if (!empty($request->get('is_part_of_package'))) { ?>
     * @group  <?= $request->get('is_part_of_package')."\n" ?>
     */
<?php } else { ?>
     */
<?php } ?>
    public function restoreManyTrashedRecords(FunctionalTester $I)
    {
        $I->wantTo('restaurar varios registros en papelera, módulo '.Page::$moduleName);

        // creo y muevo a papelera algunos registros
        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?> = factory(<?= $crud->modelClassName() ?>::class, 10)->create();
        <?= $crud->modelVariableNameFromClass($modelNamespace, 'plural') ?>->each(function ($item) {
            return $item->delete();
        });

        // si voy al Index no debe haber datos
        $I->amOnPage(Page::$moduleURL);
        $I->see(Page::$noDataFountMsg, Page::$noDataFountMsgElem);

        // envío parámetros a Index para que cargue los registros en papelera
        $I->amOnPage(
            route(
                '<?= $crud->modelPluralVariableName() ?>.index',
                [Page::$searchFieldsPrefix => ['trashed_records' => 'withTrashed']]
            )
        );
        $I->dontSee(Page::$noDataFountMsg, Page::$noDataFountMsgElem);
        // los registros en papelera se muestran con clase danger en las filas
        // de la tabla
        $I->seeElement('tbody tr.danger');
        // el botón para restaurar los registros en papelera mostrados debe
        // aparecer
        $I->see(Page::$restoreManyBtn, Page::$restoreManyBtnElem);
        
        // envío formulario de restauración todos los registros en papelera
        $I->submitForm('#restoremanyForm', [
            'id' => $<?= str_plural($crud->modelVariableName()) ?>->pluck('id')->toArray()
        ]);
        $I->dontSeeFormErrors();
        
        // soy redirigido al Index del módulo
        $I->seeCurrentUrlEquals(Page::$moduleURL);
        $I->dontSee(Page::$noDataFountMsg, Page::$noDataFountMsgElem);
    }
<?php } ?>
}