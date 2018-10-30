<div style="width: 1122px;">
	<div style="margin-top: 30px;">
		<div style="float: left; width: 60%;">
			<table style="border: none; border-collapse: collapse; font-size: 15px;">
				<tbody>
					<tr>
						<th style="width: 280px; border: 1px solid; text-align: center">加盟店様名</th>
						<th style="width: 100px; border: 1px solid; text-align: center">企業コード</th>
						<th style="width: 183px; border: 1px solid; text-align: center">電話番号</th>
						<th style="width: 148px; border: 1px solid; text-align: center">発行日</th>
					</tr>
					<tr>
						<th style="height: 20px; border: 1px solid; text-align: left">{{ $progCorp->mCorp->official_corp_name }}御中</th>
						<th style="height: 20px; border: 1px solid; text-align: center">{{ $progCorp->mCorp->id }}</th>
						<th style="height: 20px; border: 1px solid; text-align: right">{{ $progCorp->mCorp->commission_dial }}</th>
						<th style="height: 20px; border: 1px solid; text-align: center">{{ date('Y-m-d', strtotime($progCorp->created)) }}</th>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="float: right; width: 40%;">
			<table class="custom-border text-center" style="font-size: 13px; border-collapse: collapse; margin-left: 300px;">
				<tbody>
					<tr>
						<th colspan="2" style="border: 1px solid">弊 社 使 用 欄</th>
					</tr>
					<tr>
						<th style="border: 1px solid">確認者</th>
						<th style="border: 1px solid">入力者</th>
					</tr>
					<tr>
						<th style="border: 1px solid"></th>
						<th style="height: 43px; border: 1px solid"></th>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="clear: both"></div>
	</div>
</div>
