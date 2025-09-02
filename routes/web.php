<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControlller;
use App\Http\Controllers\EnviarDocControlller;
use App\Http\Controllers\DocumentacaoControlller;
use App\Http\Controllers\User\ChavesApiControlller;
use App\Http\Controllers\User\CheckoutControlller;
use App\Http\Controllers\User\FinanceiroControlller;
use App\Http\Controllers\User\RelatoriosControlller;
use App\Http\Controllers\Admin\Ajustes\LandingPageController;
use App\Http\Controllers\Admin\Ajustes\NivelController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\User\OrderbumpController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WebhookController;

Route::get('/', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'welcome']);

Route::middleware(['auth', 'verified'])->group(function () {
  Route::group(['prefix' => 'v2'], function () {
    Route::get('/', [DashboardControlller::class, 'index'])->name('dashboard.v2');
    Route::get('/produtos', [CheckoutControlller::class, 'index'])->name('profile.checkout-list.v2');
    Route::get('/orders', [OrderController::class, 'index'])->name('profile.orders.v2');
    Route::get('/chaves', [ChavesApiControlller::class, 'index'])->name('profile.chavesapi.v2');
    //como passar na rota de documentacao a versao v2 /documentacao funão index
    Route::get('/documentacao', fn () => app(DocumentacaoControlller::class)->index('v2'))->name('profile.documentacao.v2');
    Route::get('/financeiro', [FinanceiroControlller::class, 'index'])->name('profile.financeiro.v2');

    Route::group(['prefix' => 'relatorio'], function () {
        Route::get('/entradas', [RelatoriosControlller::class, 'pixentrada'])->name('profile.relatorio.pixentrada.v2');
        Route::get('/saidas', [RelatoriosControlller::class, 'pixsaida'])->name('profile.relatorio.pixsaida.v2');
        Route::get('/saidas/consulta', [RelatoriosControlller::class, 'consulta'])->name('profile.relatorio.consulta.v2');
    });

    Route::get('/webhook', [WebhookController::class, 'edit'])->name('webhook.index.v2');
    Route::group(['prefix' => 'produtos'], function () {
        Route::get('/visualizar/{id}', [CheckoutControlller::class, 'indexEdit'])->name('profile.checkout.produto.v2');
    });

    Route::get('/planos', [PlanController::class, 'index'])->name('planos.index.v2');
    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index.v2');
  });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardControlller::class, 'index'])->name('dashboard');
    Route::get('/documentacao', [DocumentacaoControlller::class, 'index'])->name('documentacao');
    Route::get('/enviar-doc', [EnviarDocControlller::class, 'index'])->name('profile.index');
    Route::post('/enviar-docs/{id}', [EnviarDocControlller::class, 'enviarDocs'])->where('id', ".*")->name('profile.enviardocs');

  	Route::get('/webhook', [WebhookController::class, 'edit'])->name('webhook.index');
    Route::post('/webhook/update', [WebhookController::class, 'update'])->name('webhook.update');

    Route::get('/planos', [PlanController::class, 'index'])->name('planos.index');
    Route::post('/planos/assinar/{plan}', [PlanController::class, 'subscribe'])->name('planos.subscribe');
    Route::get('/planos/historico', [PlanController::class, 'history'])->name('planos.history');
    Route::post('/planos/cancelar', [PlanController::class, 'cancel'])->name('planos.cancel');

    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/user/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');

    Route::group(['prefix' => 'relatorio'], function () {
        Route::get('/entradas', [RelatoriosControlller::class, 'pixentrada'])->name('profile.relatorio.pixentrada');
        Route::get('/saidas', [RelatoriosControlller::class, 'pixsaida'])->name('profile.relatorio.pixsaida');
        Route::get('/saidas/consulta', [RelatoriosControlller::class, 'consulta'])->name('profile.relatorio.consulta');
    });

    Route::get('/financeiro', [FinanceiroControlller::class, 'index'])->name('profile.financeiro');
    Route::get('/chaves', [ChavesApiControlller::class, 'index'])->name('profile.chavesapi');


    Route::group(['prefix' => 'produtos'], function () {
        Route::get('/', [CheckoutControlller::class, 'index'])->name('profile.checkout');
        Route::get('/visualizar/{id}', [CheckoutControlller::class, 'indexEdit'])->name('profile.checkout.produto');
        Route::put('/editar/{id}', [CheckoutControlller::class, 'edit'])->name('profile.checkout.produto.editar');

        Route::post('/', [CheckoutControlller::class, 'create'])->name('profile.checkout.create');


        Route::delete('checkout/{id}', [CheckoutControlller::class, 'destroy'])->name('profile.checkout.delete');

        Route::post('/depoimento/salvar', [CheckoutControlller::class, 'salvarDepoimento']);
        Route::post('/depoimento/remover', [CheckoutControlller::class, 'removerDepoimento']);
        Route::group(['prefix' => 'orderbumps'], function () {
            Route::post('create/{id}', [OrderbumpController::class, 'create'])->where('id', '.*')->name('checkout.orderbumps.create');
            Route::put('edit/{id}', [OrderbumpController::class, 'edit'])->where('id', '.*')->name('checkout.orderbumps.edit');
            Route::delete('remove/{id}', [OrderbumpController::class, 'removeBump'])->where('id', '.*')->name('checkout.orderbumps.remove');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', [OrderController::class, 'index'])->name('profile.orders');
        });
    });


    Route::group(['prefix' => 'gerencia'], function () {
        Route::get('clientes', [App\Http\Controllers\Gerencia\ClientesController::class, 'index'])->name('gerencia.index');
        Route::get('relatorio', [App\Http\Controllers\Gerencia\ClientesController::class, 'relatorio'])->name('gerencia.relatorio');
        Route::get('material', [App\Http\Controllers\Gerencia\ClientesController::class, 'material'])->name('gerencia.material');
        Route::get('/cliente/detalhes/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'detalhes'])->name('gerencia.detalhes');
        Route::post('/cliente/status', [App\Http\Controllers\Gerencia\ClientesController::class, 'usuarioStatus'])->name('gerencia.mudarstatus');
        Route::put('/cliente/edit/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'edit'])->name('gerencia.edit');
        //Route::post('/cliente/resetsenha/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'resetsenha'])->name('gerencia.resetsenha');
    });

    Route::group(['middleware' => ['auth:sanctum', 'admin'], 'prefix' => env("ADM_ROUTE")], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/usuarios', [App\Http\Controllers\Admin\UsuariosController::class, 'index'])->name('admin.usuarios');
        Route::get('/usuarios/modal/{tipo}/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'modalDinamico'])->name('admin.usuarios.modal');

        Route::get('/usuario/detalhes/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'detalhes'])->name('admin.usuario.detalhes');
        Route::post('/usuario/status', [App\Http\Controllers\Admin\UsuariosController::class, 'usuarioStatus'])->name('admin.usuarios.mudarstatus');
        Route::delete('/usuario/delete/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'destroy'])->name('admin.usuarios.delete');
        Route::put('/usuario/edit/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'edit'])->name('admin.usuarios.edit');

        Route::group(['prefix' => 'financeiro'], function () {
            Route::get('/transacoes', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'index'])->name('admin.financeiro.transacoes');
            Route::get('/carteiras', [App\Http\Controllers\Admin\Financeiro\CarteirasController::class, 'index'])->name('admin.financeiro.carteiras');
            Route::get('/entradas', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'index'])->name('admin.financeiro.entradas');
            Route::get('/saidas', [App\Http\Controllers\Admin\Financeiro\SaidasController::class, 'index'])->name('admin.financeiro.saidas');
        });

        Route::group(['prefix' => 'transacoes'], function () {
            Route::get('/procurar', [App\Http\Controllers\Admin\Transacoes\ProcurarController::class, 'index'])->name('admin.transacoes.procurar');
            Route::get('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'index'])->name('admin.transacoes.entradas');
            Route::post('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'addentrada'])->name('admin.transacoes.addentrada');
            Route::get('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'index'])->name('admin.transacoes.saidas');
            Route::post('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'addsaida'])->name('admin.transacoes.addsaida');
            Route::post('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'addsaida'])->name('admin.transacoes.addsaida');
        });

        Route::get('/aprovar-saques', [App\Http\Controllers\Admin\SaquesController::class, 'index'])->name('admin.saques');
        Route::put('/saques/aprovar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'aprovar'])->where('id', '.*')->name('admin.saques.aprovar');
        Route::put('/saques/rejeitar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'rejeitar'])->where('id', '.*')->name('admin.saques.rejeitar');

        Route::group(['prefix' => 'ajustes'], function () {
            Route::get('/adquirentes', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'index'])->name('admin.ajustes.adquirentes');
            Route::post('/cashtime', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'update'])->name('admin.adquirentes.cashtime');
            Route::post('/jupiterpay', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'update'])->name('admin.adquirentes.jupiterpay');
            Route::post('/gateway-default', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'setDefault'])->name('admin.adquirentes.default');
            Route::get('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'index'])->name('admin.landing.index');
            Route::post('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'update'])->name('admin.landing.update');
            Route::get('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'index'])->name('admin.ajustes.seguranca');
            Route::post('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'update'])->name('admin.ajustes.gerais');
            Route::post('/active-niveis', [NivelController::class, 'activeNiveis']);
            Route::group(['prefix' => 'niveis'], function () {
                Route::get('/', [NivelController::class, 'index'])->name('admin.niveis.index');
                Route::post('/', [NivelController::class, 'store']);
                Route::put('/{id}', [NivelController::class, 'update']);
                Route::delete('/{id}', [NivelController::class, 'destroy']);
            });
            Route::get('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'index'])->name('admin.ajustes.gerente');
            Route::post('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'create'])->name('admin.ajustes.gerente.add');
            Route::put('/gerentes/{id}', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'update'])->where('id', '.*')->name('admin.ajustes.gerente.update');

            Route::group(['prefix' => 'apoio'], function () {
                Route::get('/', [App\Http\Controllers\Gerencia\ApoioController::class, 'index'])->name('admin.ajustes.apoio');
                Route::post('/', [App\Http\Controllers\Gerencia\ApoioController::class, 'create'])->name('admin.ajustes.apoio.add');
                Route::put('/{id}', [App\Http\Controllers\Gerencia\ApoioController::class, 'update'])->where('id', '.*')->name('admin.ajustes.apoio.update');
                Route::delete('/{id}', [App\Http\Controllers\Gerencia\ApoioController::class, 'destroy'])->where('id', '.*')->name('admin.ajustes.apoio.delete');
            });
        });
    });
});

Route::post('/checkout/cliente/pedido/gerar', [CheckoutControlller::class, 'gerarPedido'])->name('profile.checkout.pedido.gerar');
Route::post('/checkout/cliente/pedido/status', [CheckoutControlller::class, 'statusPedido'])->name('profile.checkout.pedido.status');
Route::get('checkout/produto/v1/{id}', [CheckoutControlller::class, 'v1'])->where('id', '.*')->name('profile.checkout.v1');
Route::get('checkout/produto/v2/{id}', [CheckoutControlller::class, 'v2'])->where('id', '.*')->name('profile.checkout.v2');


require __DIR__ . '/auth.php';
require __DIR__ . '/groups/adquirentes/cashtime.php';
Route::get('/teste', function () {
    return view('test');
});

Route::get('docs', function () {
    return response()->json(json_decode(
        '{
        "openapi": "3.0.0",
        "info": {
          "title": "API de Pagamentos",
          "version": "1.0.0"
        },
        "paths": {
          "/wallet/deposit/payment": {
            "post": {
              "tags": ["cash-in"],
              "summary": "Gerar QrCode",
              "parameters": [
                {
                  "name": "Accept",
                  "in": "header",
                  "required": true,
                  "schema": {
                    "type": "string",
                    "default": "application/json"
                  }
                },
                {
                  "name": "Content-Type",
                  "in": "header",
                  "required": true,
                  "schema": {
                    "type": "string",
                    "default": "application/json"
                  }
                }
              ],
              "requestBody": {
                "required": true,
                "content": {
                  "application/json": {
                    "schema": {
                      "type": "object",
                      "required": [
                        "token", "secret", "amount", "debtor_name",
                        "email", "debtor_document_number", "phone",
                        "method_pay", "postback"
                      ],
                      "properties": {
                        "token": { "type": "string", "example": "abc123token" },
                        "secret": { "type": "string", "example": "mySecretKey" },
                        "amount": { "type": "number", "format": "float", "example": 100.5 },
                        "debtor_name": { "type": "string", "example": "João Silva" },
                        "email": { "type": "string", "format": "email", "example": "joao@email.com" },
                        "debtor_document_number": { "type": "string", "example": "12345678900" },
                        "phone": { "type": "string", "example": "+5511999999999" },
                        "method_pay": { "type": "string", "example": "pix" },
                        "postback": { "type": "string", "example": "https://minhaapi.com/callback" }
                      }
                    }
                  }
                }
              },
              "responses": {
                "200": {
                  "description": "Retorna os dados do QrCode",
                  "content": {
                    "application/json": {
                      "schema": {
                        "type": "object",
                        "properties": {
                          "idTransaction": { "type": "string", "example": "TX123" },
                          "qrcode": { "type": "string", "example": "código copia e cola" },
                          "qr_code_image_url": { "type": "string", "example": "https://exemplo.com/qrcode.png" }
                        }
                      }
                    }
                  }
                }
              }
            }
          },
          "/wallet/pixout": {
            "post": {
              "tags": ["cash-out"],
              "summary": "Efetuar Saque via Pix",
              "parameters": [
                {
                  "name": "Accept",
                  "in": "header",
                  "required": true,
                  "schema": {
                    "type": "string",
                    "default": "application/json"
                  }
                },
                {
                  "name": "Content-Type",
                  "in": "header",
                  "required": true,
                  "schema": {
                    "type": "string",
                    "default": "application/json"
                  }
                }
              ],
              "requestBody": {
                "required": true,
                "content": {
                  "application/json": {
                    "schema": {
                      "type": "object",
                      "required": [
                        "token", "secret", "amount",
                        "pixKey", "pixKeyType", "baasPostbackUrl"
                      ],
                      "properties": {
                        "token": { "type": "string", "example": "abc123token" },
                        "secret": { "type": "string", "example": "mySecretKey" },
                        "amount": { "type": "number", "example": 100 },
                        "pixKey": { "type": "string", "example": "12345678900" },
                        "pixKeyType": {
                          "type": "string",
                          "enum": ["cpf", "email", "telefone", "aleatoria"],
                          "example": "cpf"
                        },
                        "baasPostbackUrl": { "type": "string", "example": "https://minhaapi.com/pixout/callback" }
                      }
                    }
                  }
                }
              },
              "responses": {
                "200": {
                  "description": "Retorna os dados da transação PixOut",
                  "content": {
                    "application/json": {
                      "schema": {
                        "type": "object",
                        "properties": {
                          "id": { "type": "string", "example": "b522a295-e404..." },
                          "amount": { "type": "number", "example": 100 },
                          "pixKey": { "type": "string", "example": "chave" },
                          "pixKeyType": { "type": "string", "example": "cpf" },
                          "withdrawStatusId": { "type": "string", "example": "PendingProcessing" },
                          "createdAt": { "type": "string", "format": "date-time", "example": "2025-04-19T20:04:53.166Z" },
                          "updatedAt": { "type": "string", "format": "date-time", "example": "2025-04-19T20:04:53.166Z" }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }'
    ));
});
