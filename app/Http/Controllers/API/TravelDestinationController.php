<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\TravelUpdated;
use App\Http\Resources\TravelDestinationResource;
use App\Models\TravelDestination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TravelDestinationController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'destination' => 'nullable|string|max:255',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
        ];

        $messages = [
            'destination.string' => 'O destino deve ser uma string válida.',
            'start_date.date' => 'A data de partida deve ser uma data válida.',
            'start_date.date_format' => 'A data de partida deve estar no formato YYYY-MM-DD.',
            'end_date.date' => 'A data de retorno deve ser uma data válida.',
            'end_date.date_format' => 'A data de retorno deve estar no formato YYYY-MM-DD.',
            'end_date.after_or_equal' => 'A data de retorno não pode ser anterior à data de partida.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = TravelDestination::query();

            if ($request->has('destination')) {
                $query->where('destination', 'like', '%' . $request->destination . '%');
            }
            if ($request->has('start_date')) {
                $query->where('departure_date', '>=', $request->start_date);
            }
            if ($request->has('end_date')) {
                $query->where('return_date', '<=', $request->end_date);
            }

            if(!auth()->user()->hasAnyRole('admin') && !auth()->user()->hasAnyPermission('admin')){
                $query->where('user_id', auth()->user()->id);
            }else{
                if ($request->has('user_id')) {
                    $query->where('user_id', $request->input('user_id'));
                }
            }

            $travelDestinations = $query->with('user')->get();
            return TravelDestinationResource::collection($travelDestinations);

        }catch (\Exception $e) {
            Log::error('Erro ao buscar destino de viagem.', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.',
            ], 500);
        }

    }

    public function store(Request $request)
    {
        $rules = [
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after_or_equal:departure_date', // use `departure_date` field here
        ];

        $messages = [
            'user_id.required' => 'O ID do usuário é obrigatório.',
            'user_id.int' => 'O ID do usuário deve ser um número inteiro.',
            'destination.required' => 'O destino é obrigatório.',
            'destination.string' => 'O destino deve ser uma string.',
            'destination.max' => 'O destino não pode ter mais de 255 caracteres.',
            'departure_date.required' => 'A data de partida é obrigatória.',
            'departure_date.date' => 'A data de partida deve ser uma data válida.',
            'departure_date.after_or_equal' => 'A data de partida deve ser hoje ou futura.',
            'return_date.date' => 'A data de retorno deve ser uma data válida.',
            'return_date.after_or_equal' => 'A data de retorno não pode ser anterior à data de partida.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if(!auth()->user()->hasAnyRole('admin') && !auth()->user()->hasAnyPermission('admin')){
            $request['user_id']=auth()->user()->id;
        }else{
            if ($request->has('user_id')) {
                $request['user_id']=$request->input('user_id');
            }else{
                $request['user_id']=auth()->user()->id;
            }
        }

        try{
            $travelDestination = TravelDestination::create($request->all());
            return response(new TravelDestinationResource($travelDestination), 200);
        }
        catch (\Exception $e) {
            Log::error('Erro ao criar destino de viagem.', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.',
            ], 500);
        }

    }

    public function update(Request $request,$id){

        $travelDestination = TravelDestination::findOrFail($id);

        $rules = [
            'user_id' => 'sometimes|integer|exists:users,id',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
        ];

        $messages = [
            'user_id.integer' => 'O ID do usuário deve ser um número inteiro.',
            'user_id.exists' => 'O usuário fornecido não existe.',
            'destination.required' => 'O destino é obrigatório.',
            'destination.string' => 'O destino deve ser uma string.',
            'destination.max' => 'O destino não pode ter mais de 255 caracteres.',
            'departure_date.required' => 'A data de partida é obrigatória.',
            'departure_date.date' => 'A data de partida deve ser uma data válida.',
            'departure_date.after_or_equal' => 'A data de partida deve ser hoje ou futura.',
            'return_date.date' => 'A data de retorno deve ser uma data válida.',
            'return_date.after_or_equal' => 'A data de retorno não pode ser anterior à data de partida.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validação falhou',
                'messages' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        $travelDestination=TravelDestination::findOrFail($id);

        if(!auth()->user()->hasAnyRole('admin') && !auth()->user()->hasAnyPermission('admin')){
             if(!$travelDestination->user_id==auth()->user()->id)
             {
                 return response()->json(['error' => 'Não é possível alterar uma solicitação de outro usuário','messages' => $validator->errors(),], 401);
             }
            $travelDestination->update($validator->validated());
        }else{
            $travelDestination->update($validator->validated());
        }
        return response(new TravelDestinationResource($travelDestination), 200);
    }

    public function updateStatus(Request $request, string $id)
    {
        $rules = [
            'status' => 'required|in:aprovado,cancelado',
        ];

        $messages = [
            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O campo status deve ser uma dessas opções ("aprovado" ou "cancelado").',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $destination = TravelDestination::findOrFail($id);

        if ($destination->status == 'aprovado' && $request->status == 'cancelado') {
            return response()->json(['message' => 'Para cancelar um pedido de viagem já aprovado entre em contato com um responsável.'], 400);
        }

        if ($destination->user_id == auth()->user()->id) {
            return response()->json(['message' => 'Você não pode cancelar ou aprovar seus próprios pedidos.'], 400);
        }

        try{
            $destination->update($request->all());
            try {
                Mail::to($destination->user->email)->send(new TravelUpdated($destination->user, $destination));
            }catch (\Exception $e){
                dd($e->getMessage());
            }


            return response()->json(['message' => 'Solicitação de Viagem alterada com sucesso.']);
        }
        catch (\Exception $e) {
            Log::error('Erro ao alterar solicitação de viagem.', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.',
            ], 500);
        }

    }

    public function checkStatus(string $id)
    {

        $destination = TravelDestination::findOrFail($id);
        try{
            switch ($destination->status){
                case 'aprovado':
                    $msg='Destino consta como aprovado.';
                    break;
                case 'solicitado':
                    $msg='Destino está como solicitado.';
                    break;
                case 'cancelado':
                    $msg='Destino consta como cancelado.';
                    break;
            }
        }
        catch (\Exception $e) {
            Log::error('Erro ao checkar o status do pedido de viagem.', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.',
            ], 500);
        }
        return response()->json(['message' => $msg,'status'=>$destination->status],200);
    }

    public function test(){
        $user=User::findOrFail(1);
        $travelInfo['departure_date']='123123';
        $travelInfo['return_date']='122222';
        $travelInfo['status']='aprovado';
        $travelInfo['destination']='Aguas da Prata';


    }

}
