insert into
	demand_forecasts
	(
		state_id,
		region_id,
		forecast_date,
		genre0,
		genre1,
		genre2,
		display_date,
		demand_count0_min,
		demand_count0_max,
		demand_count1_min,
		demand_count1_max,
		demand_count2_min,
		demand_count2_max,
		wind_speed_level,
		created,
		modified
	)
select
	wf.state_id,
	wf.region_id,
	cast(wf.forecast_date as date),
	490 category0,
	519 category1,
	551 category2,
	cast(current_date as date)display_date,
	coalesce(trunc(avg(da.min_0)+ .9, 0),0)demand_count0_min,
	coalesce(trunc(avg(da.max_0)+ .9, 0),0)demand_count0_max,
	coalesce(trunc(avg(da.min_1)+ .9, 0),0)demand_count1_min,
	coalesce(trunc(avg(da.max_1)+ .9, 0),0)demand_count1_max,
	coalesce(trunc(avg(da.min_2)+ .9, 0),0)demand_count2_min,
	coalesce(trunc(avg(da.max_2)+ .9, 0),0)demand_count2_max,
	wf.wl,
	now() created,
	now() modified
from
(
select
	to_char(forecast_datetime, 'YYYY-MM-DD')forecast_date,
	state_id,
	trunc(avg(wind_speed_level)) wl ,
	region_id
from
	weather_forecasts
		where
	forecast_datetime >= current_date
		group by
	to_char(forecast_datetime, 'YYYY-MM-DD'),
	state_id,
	region_id
)wf
left join
(
select
	state_id,
	region_id,
	case
		when wind_speed_avg between 0 and 1.2 then demand_count0
		when wind_speed_avg between 2.6 and 4 then demand_count0
		when wind_speed_avg between 5.6 and 7.5 then demand_count0
		when wind_speed_avg > 9.5 then demand_count0
	end as min_0,
	case
		when wind_speed_avg between 1.3 and 2.5 then demand_count0
		when wind_speed_avg between 4.1 and 5.5 then demand_count0
		when wind_speed_avg between 7.6 and 9.5 then demand_count0
	end as max_0,
	case
		when wind_speed_avg between 0 and 1.2 then demand_count1
		when wind_speed_avg between 2.6 and 4 then demand_count1
		when wind_speed_avg between 5.6 and 7.5 then demand_count1
		when wind_speed_avg > 9.5 then demand_count1
	end as min_1,
	case
		when wind_speed_avg between 1.3 and 2.5 then demand_count1
		when wind_speed_avg between 4.1 and 5.5 then demand_count1
		when wind_speed_avg between 7.6 and 9.5 then demand_count1
	end as max_1,
	case
		when wind_speed_avg between 0 and 1.2 then demand_count2
		when wind_speed_avg between 2.6 and 4 then demand_count2
		when wind_speed_avg between 5.6 and 7.5 then demand_count2
		when wind_speed_avg > 9.5 then demand_count2
	end as min_2,
	case
		when wind_speed_avg between 1.3 and 2.5 then demand_count2
		when wind_speed_avg between 4.1 and 5.5 then demand_count2
		when wind_speed_avg between 7.6 and 9.5 then demand_count2
	end as max_2,
	case
		when wind_speed_avg between 0 and 2.5 then 1
		when wind_speed_avg between 2.6 and 5.5 then 2
		when wind_speed_avg between 5.6 and 9.5 then 3
		when wind_speed_avg > 9.5 then 4
	end as wl

		from
	demand_actuals
where
	actual_datetime  >= (now() - interval '1 year')
		)da
	on
	da.state_id = wf.state_id and
	da.wl = wf.wl
	group by
	wf.state_id,
	wf.forecast_date,
	wf.region_id,
	wf.wl
	order by
	wf.state_id,
	wf.forecast_date
;