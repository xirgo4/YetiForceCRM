<?php

/**
 * Portal container - Get modules list action file.
 *
 * @package API
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace Api\Portal\BaseAction;

use OpenApi\Annotations as OA;

/**
 * Portal container - Get modules list action class.
 */
class Modules extends \Api\RestApi\BaseAction\Modules
{
	/**
	 * Get permitted modules.
	 *
	 * @return array
	 *
	 * @OA\Get(
	 *		path="/webservice/Portal/Modules",
	 *		description="Get the permitted module list action, along with their translated action",
	 *		summary="The allowed actions of the module list",
	 *		tags={"BaseAction"},
	 *		security={
	 *			{"basicAuth" : {}, "ApiKeyAuth" : {}, "token" : {}}
	 *		},
	 *		@OA\Parameter(
	 *			name="X-ENCRYPTED",
	 *			in="header",
	 *			required=true,
	 *			@OA\Schema(ref="#/components/schemas/X-ENCRYPTED")
	 *		),
	 *		@OA\Response(
	 *			response=200,
	 *			description="List of permitted modules",
	 *			@OA\JsonContent(ref="#/components/schemas/BaseAction_Modules_ResponseBody"),
	 *			@OA\XmlContent(ref="#/components/schemas/BaseAction_Modules_ResponseBody"),
	 *		),
	 *		@OA\Response(
	 *			response=401,
	 *			description="No sent token OR Invalid token",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception")
	 *		),
	 *		@OA\Response(
	 *			response=403,
	 *			description="No permissions for module",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception")
	 *		),
	 * ),
	 * @OA\Schema(
	 *		schema="BaseAction_Modules_ResponseBody",
	 *		title="Base action - List of permitted modules",
	 *		description="List of available modules",
	 *		type="object",
	 *		@OA\Property(
	 *			property="status",
	 * 			description="A numeric value of 0 or 1 that indicates whether the communication is valid. 1 - success , 0 - error",
	 * 			enum={0, 1},
	 *     	  	type="integer",
	 * 			example=1
	 * 		),
	 *		@OA\Property(
	 *			property="result",
	 *			description="List of permitted modules",
	 *			type="object",
	 *			@OA\AdditionalProperties(description="Module name", type="string", example="Accounts"),
	 * 		),
	 *	),
	 */
	public function get(): array
	{
		return parent::get();
	}
}